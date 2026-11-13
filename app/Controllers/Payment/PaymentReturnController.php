<?php

namespace App\Controllers\Payment;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Services\MidtransService;
use Throwable;

class PaymentReturnController extends BaseController
{
    /**
     * Midtrans sends the provider transaction order_id in the query string.
     * Never mark an order paid from transaction_status/status_code in the URL.
     * Ask Midtrans server-to-server and persist the verified result instead.
     */
    public function finish()
    {
        $providerOrderId = trim((string) $this->request->getGet('order_id'));

        if ($providerOrderId === '') {
            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'ID transaksi tidak ditemukan. Periksa status pesanan Anda.');
        }

        try {
            $service = new MidtransService();
            $result = $service->syncTransactionStatus($providerOrderId);

            $payment = (new PaymentModel())->findByProviderOrderId($providerOrderId);
            if ($payment) {
                $order = (new OrderModel())->find((int) $payment['order_id']);
                if ($order && ! empty($order['order_number'])) {
                    $target = site_url('customer/orders/' . rawurlencode($order['order_number']));

                    if (($result['success'] ?? false) && ($order['payment_status'] ?? '') === 'paid') {
                        return redirect()->to($target)
                            ->with('success', 'Pembayaran berhasil diverifikasi oleh Midtrans.');
                    }

                    if (($order['payment_status'] ?? '') === 'paid') {
                        return redirect()->to($target)
                            ->with('success', 'Pembayaran pesanan sudah terverifikasi.');
                    }

                    return redirect()->to($target)->with(
                        'warning',
                        $result['message'] ?? 'Pembayaran masih diproses. Muat ulang halaman beberapa saat lagi.'
                    );
                }
            }

            log_message('warning', '[MIDTRANS RETURN] Local payment/order not found for ' . $providerOrderId);
            return redirect()->to(site_url('customer/orders'))
                ->with('warning', 'Pembayaran telah kembali dari Midtrans, tetapi pesanan lokal tidak ditemukan.');
        } catch (Throwable $e) {
            log_message(
                'error',
                '[MIDTRANS FINISH CONTROLLER ERROR] ' . $e->getMessage()
                . ' | ' . $e->getFile() . ':' . $e->getLine()
            );

            return redirect()->to(site_url('customer/orders'))
                ->with('error', 'Pembayaran kembali dari Midtrans, tetapi sinkronisasi gagal. Periksa log aplikasi.');
        }
    }

    public function unfinish()
    {
        $providerOrderId = trim((string) $this->request->getGet('order_id'));
        return view('payment/unfinish', ['orderId' => $providerOrderId]);
    }

    public function error()
    {
        $providerOrderId = trim((string) $this->request->getGet('order_id'));
        return view('payment/error', ['orderId' => $providerOrderId]);
    }
}
