<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\OrderStatusHistoryModel;
use App\Models\PaymentModel;
use App\Models\PaymentNotificationModel;
use Config\Midtrans as MidtransConfig;
use Midtrans\Config as SdkConfig;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;
use Throwable;

class MidtransService
{
    protected MidtransConfig $config;
    protected PaymentModel $paymentModel;
    protected OrderModel $orderModel;
    protected PaymentNotificationModel $notificationModel;
    protected OrderStatusHistoryModel $historyModel;

    public function __construct()
    {
        $this->config = config('Midtrans') ?? new MidtransConfig();
        $this->paymentModel = new PaymentModel();
        $this->orderModel = new OrderModel();
        $this->notificationModel = new PaymentNotificationModel();
        $this->historyModel = new OrderStatusHistoryModel();
    }

    /**
     * Configure Midtrans SDK for server-to-server operations.
     */
    private function configureSdk(): void
    {
        if (
            ! class_exists(SdkConfig::class) ||
            ! class_exists(Snap::class) ||
            ! class_exists(Transaction::class)
        ) {
            throw new RuntimeException(
                'Midtrans SDK belum terpasang. Jalankan composer require midtrans/midtrans-php.'
            );
        }

        $serverKey = trim((string) $this->config->serverKey);
        $clientKey = trim((string) $this->config->clientKey);

        if ($serverKey === '' || $clientKey === '') {
            throw new RuntimeException('Server Key dan Client Key Midtrans wajib diisi pada file .env.');
        }

        // Catch an obvious Sandbox/Production key mismatch before calling Midtrans.
        $expectedServerPrefix = $this->config->isProduction ? 'Mid-server-' : 'SB-Mid-server-';
        $expectedClientPrefix = $this->config->isProduction ? 'Mid-client-' : 'SB-Mid-client-';

        if (! str_starts_with($serverKey, $expectedServerPrefix)) {
            throw new RuntimeException(
                'Prefix Server Key tidak cocok dengan mode '
                . ($this->config->isProduction ? 'Production' : 'Sandbox')
                . '. Periksa Settings > Access Keys pada dashboard Midtrans.'
            );
        }

        if (! str_starts_with($clientKey, $expectedClientPrefix)) {
            throw new RuntimeException(
                'Prefix Client Key tidak cocok dengan mode '
                . ($this->config->isProduction ? 'Production' : 'Sandbox')
                . '. Server Key dan Client Key harus berasal dari environment yang sama.'
            );
        }

        SdkConfig::$serverKey = $serverKey;
        SdkConfig::$isProduction = (bool) $this->config->isProduction;
        SdkConfig::$isSanitized = (bool) $this->config->isSanitized;
        SdkConfig::$is3ds = (bool) $this->config->is3ds;
    }

    public function getServerKey(): string
    {
        return (string) $this->config->serverKey;
    }

    public function getClientKey(): string
    {
        return (string) $this->config->clientKey;
    }

    public function getSnapJsUrl(): string
    {
        return $this->config->getSnapUrl();
    }

    public function generateProviderOrderId(string $orderNumber): string
    {
        return 'VL-'
            . preg_replace('/[^A-Za-z0-9-]/', '', $orderNumber)
            . '-'
            . strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Create a Midtrans Snap transaction and persist its token.
     */
    public function createSnapToken(array $order, array $user): array
    {
        if (
            trim((string) $this->config->serverKey) === '' ||
            trim((string) $this->config->clientKey) === ''
        ) {
            return [
                'success' => false,
                'error' => 'Kredensial Midtrans belum dikonfigurasi pada file .env.',
            ];
        }

        if (($order['payment_status'] ?? '') === 'paid') {
            return ['success' => false, 'error' => 'Pesanan ini sudah dibayar.'];
        }

        if (
            empty($order['id']) ||
            empty($order['order_number']) ||
            ! isset($order['grand_total'])
        ) {
            return ['success' => false, 'error' => 'Data pesanan tidak lengkap.'];
        }

        $orderId = (int) $order['id'];
        $grossAmount = (int) round((float) $order['grand_total']);

        if ($grossAmount < 1) {
            return ['success' => false, 'error' => 'Nominal pembayaran tidak valid.'];
        }

        try {
            $existing = $this->paymentModel
                ->where('order_id', $orderId)
                ->whereIn('payment_status', ['pending', 'unpaid'])
                ->where('snap_token IS NOT NULL', null, false)
                ->orderBy('id', 'DESC')
                ->first();

            if ($existing && ! empty($existing['snap_token'])) {
                return [
                    'success' => true,
                    'snap_token' => $existing['snap_token'],
                    'provider_order_id' => $existing['provider_order_id'],
                    'client_key' => $this->getClientKey(),
                ];
            }
        } catch (Throwable $e) {
            log_message('error', '[MIDTRANS EXISTING PAYMENT ERROR] ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Gagal memeriksa riwayat pembayaran pada database.',
            ];
        }

        try {
            $providerOrderId = $this->generateProviderOrderId((string) $order['order_number']);

            $customerDetails = [
                'first_name' => mb_substr(
                    (string) ($user['name'] ?? 'Pelanggan Velvoria'),
                    0,
                    50
                ),
            ];

            $email = trim((string) ($user['email'] ?? ''));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $customerDetails['email'] = $email;
            }

            $phone = trim((string) ($user['phone'] ?? ''));
            if ($phone !== '') {
                $customerDetails['phone'] = $phone;
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $providerOrderId,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => $customerDetails,
                // Force the customer back to this application instead of a stale
                // Finish URL configured in the Midtrans dashboard (e.g. eventku1.test).
                'callbacks' => [
                    'finish' => site_url('payment/finish'),
                    'error'  => site_url('payment/error'),
                ],
                'item_details' => [[
                    'id' => substr(
                        preg_replace(
                            '/[^A-Za-z0-9_-]/',
                            '',
                            (string) $order['order_number']
                        ),
                        0,
                        50
                    ),
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'Pembayaran pesanan Velvoria',
                ]],
            ];

            $this->configureSdk();
            $snapToken = Snap::getSnapToken($params);

            if (! is_string($snapToken) || trim($snapToken) === '') {
                throw new RuntimeException('Midtrans tidak mengembalikan Snap Token.');
            }

            $paymentId = $this->paymentModel->insert([
                'order_id' => $orderId,
                'provider' => 'midtrans',
                'provider_order_id' => $providerOrderId,
                'gross_amount' => $grossAmount,
                'snap_token' => $snapToken,
                'transaction_status' => 'pending',
                'payment_status' => 'pending',
            ], true);

            if (! $paymentId) {
                log_message(
                    'error',
                    '[MIDTRANS PAYMENT INSERT ERROR] '
                    . json_encode($this->paymentModel->errors())
                );

                return [
                    'success' => false,
                    'error' => 'Token berhasil dibuat, tetapi gagal disimpan ke database.',
                ];
            }

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'provider_order_id' => $providerOrderId,
                'client_key' => $this->getClientKey(),
            ];
        } catch (Throwable $e) {
            log_message(
                'error',
                '[MIDTRANS CREATE TOKEN ERROR] '
                . get_class($e)
                . ' | ' . $e->getMessage()
                . ' | File: ' . $e->getFile()
                . ' | Line: ' . $e->getLine()
            );

            $rawMessage = $e->getMessage();

            // HTTP 401 is an authentication failure from Midtrans, not a browser/Snap.js issue.
            if (
                preg_match('/\\b401\\b/', $rawMessage) === 1 ||
                stripos($rawMessage, 'unauthorized') !== false ||
                stripos($rawMessage, 'access denied') !== false
            ) {
                $errorMessage = 'Midtrans menolak autentikasi (HTTP 401). Server Key Sandbox yang dibaca aplikasi tidak valid, tidak aktif, atau bukan pasangan environment/akun yang benar. Ambil Server Key dan Client Key dari dashboard merchant yang sama pada mode Sandbox, lalu pastikan midtrans.isProduction = false.';
            } else {
                $errorMessage = ENVIRONMENT === 'development'
                    ? 'Midtrans Error: ' . $rawMessage
                    : 'Tidak dapat membuat transaksi Midtrans. Silakan coba kembali.';
            }

            return ['success' => false, 'error' => $errorMessage];
        }
    }

    /**
     * Reconcile a transaction after the customer's browser returns from Snap.
     * Browser query parameters are never trusted as proof of payment: the status
     * is fetched server-to-server from Midtrans, then passed through the same
     * signature and amount verification path as a webhook.
     */
    public function syncTransactionStatus(string $providerOrderId): array
    {
        $providerOrderId = trim($providerOrderId);
        if ($providerOrderId === '') {
            return ['success' => false, 'message' => 'ID transaksi Midtrans tidak ditemukan.'];
        }

        $payment = $this->paymentModel->findByProviderOrderId($providerOrderId);
        if (! $payment) {
            return ['success' => false, 'message' => 'Transaksi pembayaran tidak ditemukan di Velvoria.'];
        }

        try {
            $this->configureSdk();
            $status = Transaction::status($providerOrderId);
            $status = json_decode(json_encode($status), true) ?: [];

            if (($status['order_id'] ?? '') !== $providerOrderId) {
                return ['success' => false, 'message' => 'Order ID dari Midtrans tidak cocok.'];
            }

            $statusCode = (string) ($status['status_code'] ?? '');
            $grossAmount = (string) ($status['gross_amount'] ?? '');
            if ($statusCode === '' || $grossAmount === '') {
                return ['success' => false, 'message' => 'Respons status Midtrans tidak lengkap.'];
            }

            $payload = [
                'order_id' => $providerOrderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'signature_key' => hash(
                    'sha512',
                    $providerOrderId . $statusCode . $grossAmount . $this->config->serverKey
                ),
            ];

            // handleNotification independently fetches the status again and
            // persists only the server-verified result.
            return $this->handleNotification($payload);
        } catch (Throwable $e) {
            log_message(
                'error',
                '[MIDTRANS RETURN RECONCILIATION ERROR] '
                . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine()
            );

            return [
                'success' => false,
                'message' => ENVIRONMENT === 'development'
                    ? 'Gagal menyinkronkan pembayaran: ' . $e->getMessage()
                    : 'Status pembayaran belum dapat disinkronkan. Silakan muat ulang beberapa saat lagi.',
            ];
        }
    }

    /**
     * Verify and process Midtrans notification.
     */
    public function handleNotification(array $payload): array
    {
        $providerOrderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');

        if (
            $providerOrderId === '' ||
            $statusCode === '' ||
            $grossAmount === '' ||
            $signature === ''
        ) {
            return ['success' => false, 'message' => 'Payload notifikasi tidak lengkap.'];
        }

        if (trim((string) $this->config->serverKey) === '') {
            return ['success' => false, 'message' => 'Server Key Midtrans belum dikonfigurasi.'];
        }

        $expectedSignature = hash(
            'sha512',
            $providerOrderId . $statusCode . $grossAmount . $this->config->serverKey
        );

        if (! hash_equals($expectedSignature, $signature)) {
            log_message('critical', '[MIDTRANS WEBHOOK] Signature tidak valid: ' . $providerOrderId);

            return ['success' => false, 'message' => 'Signature notifikasi tidak valid.'];
        }

        $payment = $this->paymentModel->findByProviderOrderId($providerOrderId);

        if (! $payment) {
            return ['success' => false, 'message' => 'Transaksi tidak ditemukan.'];
        }

        try {
            $this->configureSdk();
            $verified = Transaction::status($providerOrderId);
            $verified = json_decode(json_encode($verified), true) ?: [];
        } catch (Throwable $e) {
            log_message('error', '[MIDTRANS STATUS VERIFICATION ERROR] ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Status transaksi belum dapat diverifikasi ke Midtrans.',
            ];
        }

        if (($verified['order_id'] ?? '') !== $providerOrderId) {
            return ['success' => false, 'message' => 'Order ID hasil verifikasi tidak cocok.'];
        }

        $verifiedAmount = (int) round((float) ($verified['gross_amount'] ?? 0));

        if ($verifiedAmount !== (int) $payment['gross_amount']) {
            log_message('critical', '[MIDTRANS AMOUNT MISMATCH] ' . $providerOrderId);

            return ['success' => false, 'message' => 'Nominal transaksi tidak cocok.'];
        }

        $transactionStatus = (string) ($verified['transaction_status'] ?? '');
        $fraudStatus = (string) ($verified['fraud_status'] ?? '');

        $paymentStatus = match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'pending',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'refund' => 'refunded',
            'partial_refund' => 'partially_refunded',
            default => null,
        };

        if ($paymentStatus === null) {
            return ['success' => false, 'message' => 'Status transaksi Midtrans tidak dikenali.'];
        }

        $notificationHash = hash(
            'sha256',
            json_encode($verified, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        if ($this->notificationModel->hashExists($notificationHash)) {
            return ['success' => true, 'message' => 'Notifikasi duplikat telah diabaikan.'];
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $lockedPayment = $db
                ->table('payments')
                ->where('id', $payment['id'])
                ->get()
                ->getRowArray();

            if (! $lockedPayment) {
                $db->transRollback();

                return ['success' => false, 'message' => 'Data pembayaran tidak ditemukan.'];
            }

            $paymentUpdate = [
                'transaction_id' => $verified['transaction_id'] ?? null,
                'payment_type' => $verified['payment_type'] ?? null,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus ?: null,
                'payment_status' => $paymentStatus,
            ];

            if ($paymentStatus === 'paid' && empty($lockedPayment['paid_at'])) {
                $paymentUpdate['paid_at'] = date('Y-m-d H:i:s');
            }

            $this->paymentModel->update($payment['id'], $paymentUpdate);

            $order = $this->orderModel->find($payment['order_id']);

            if ($order) {
                $orderUpdate = ['payment_status' => $paymentStatus];

                if (
                    $paymentStatus === 'paid' &&
                    $order['order_status'] === OrderStatusService::STATUS_AWAITING_PAYMENT
                ) {
                    $orderUpdate['order_status'] = OrderStatusService::STATUS_PENDING_PICKUP;

                    $this->historyModel->insert([
                        'order_id' => $order['id'],
                        'old_status' => OrderStatusService::STATUS_AWAITING_PAYMENT,
                        'new_status' => OrderStatusService::STATUS_PENDING_PICKUP,
                        'changed_by' => null,
                        'note' => 'Pembayaran terverifikasi oleh Midtrans.',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                $this->orderModel->update($order['id'], $orderUpdate);
            }

            $this->notificationModel->insert([
                'payment_id' => $payment['id'],
                'provider_order_id' => $providerOrderId,
                'notification_hash' => $notificationHash,
                'event_status' => $transactionStatus,
                'payload_json' => json_encode(
                    $verified,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                ),
                'processed_at' => date('Y-m-d H:i:s'),
                'processing_result' => 'Payment status updated to ' . $paymentStatus,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new RuntimeException('Database transaction failed.');
            }

            $db->transCommit();

            return [
                'success' => true,
                'message' => 'Notifikasi pembayaran berhasil diverifikasi dan diproses.',
            ];
        } catch (Throwable $e) {
            $db->transRollback();

            log_message(
                'error',
                '[MIDTRANS NOTIFICATION TRANSACTION ERROR] '
                . $e->getMessage()
                . ' | File: ' . $e->getFile()
                . ' | Line: ' . $e->getLine()
            );

            return ['success' => false, 'message' => 'Gagal menyimpan status pembayaran.'];
        }
    }
}
