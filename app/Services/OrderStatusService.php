<?php

namespace App\Services;

use App\Models\OrderModel;
use App\Models\OrderStatusHistoryModel;

class OrderStatusService
{
    public const STATUS_AWAITING_PAYMENT = 'awaiting_payment';
    public const STATUS_PENDING_PICKUP   = 'pending_pickup';
    public const STATUS_RECEIVED         = 'received';
    public const STATUS_WASHING          = 'washing';
    public const STATUS_DRYING           = 'drying';
    public const STATUS_IRONING          = 'ironing';
    public const STATUS_READY            = 'ready';
    public const STATUS_DELIVERING       = 'delivering';
    public const STATUS_COMPLETED        = 'completed';
    public const STATUS_CANCELLED        = 'cancelled';
    public const STATUS_REJECTED         = 'rejected';

    public const ALLOWED_TRANSITIONS = [
        self::STATUS_AWAITING_PAYMENT => [self::STATUS_PENDING_PICKUP, self::STATUS_CANCELLED, self::STATUS_REJECTED],
        self::STATUS_PENDING_PICKUP   => [self::STATUS_RECEIVED, self::STATUS_CANCELLED, self::STATUS_REJECTED],
        self::STATUS_RECEIVED         => [self::STATUS_WASHING, self::STATUS_CANCELLED],
        self::STATUS_WASHING          => [self::STATUS_DRYING],
        self::STATUS_DRYING           => [self::STATUS_IRONING],
        self::STATUS_IRONING          => [self::STATUS_READY],
        self::STATUS_READY            => [self::STATUS_DELIVERING, self::STATUS_COMPLETED],
        self::STATUS_DELIVERING       => [self::STATUS_COMPLETED],
        self::STATUS_COMPLETED        => [],
        self::STATUS_CANCELLED        => [],
        self::STATUS_REJECTED         => [],
    ];

    protected OrderModel $orderModel;
    protected OrderStatusHistoryModel $historyModel;

    public function __construct()
    {
        $this->orderModel   = new OrderModel();
        $this->historyModel = new OrderStatusHistoryModel();
    }

    public function canTransition(string $from, string $to): bool
    {
        $allowed = self::ALLOWED_TRANSITIONS[$from] ?? [];
        return in_array($to, $allowed, true);
    }

    public function getNextAllowedStatuses(string $currentStatus): array
    {
        return self::ALLOWED_TRANSITIONS[$currentStatus] ?? [];
    }

    public function transition(int $orderId, string $newStatus, ?int $changedBy = null, ?string $note = null): array
    {
        $order = $this->orderModel->find($orderId);
        if (! $order) {
            return ['success' => false, 'error' => 'Pesanan tidak ditemukan.'];
        }

        $currentStatus = $order['order_status'];

        if (! $this->canTransition($currentStatus, $newStatus)) {
            return [
                'success' => false,
                'error'   => "Transisi status dari '{$this->getStatusLabel($currentStatus)}' ke '{$this->getStatusLabel($newStatus)}' tidak diperbolehkan.",
            ];
        }

        $db = db_connect();
        $db->transStart();

        $updateData = [
            'order_status' => $newStatus,
        ];

        if ($newStatus === self::STATUS_COMPLETED) {
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        }

        $this->orderModel->update($orderId, $updateData);

        $this->historyModel->insert([
            'order_id'   => $orderId,
            'old_status' => $currentStatus,
            'new_status' => $newStatus,
            'changed_by' => $changedBy,
            'note'       => $note,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['success' => false, 'error' => 'Gagal memperbarui status di database.'];
        }

        return ['success' => true, 'message' => 'Status pesanan berhasil diperbarui.'];
    }

    public function getStatusLabel(string $status): string
    {
        $labels = [
            self::STATUS_AWAITING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_PENDING_PICKUP   => 'Menunggu Penjemputan',
            self::STATUS_RECEIVED         => 'Cucian Diterima',
            self::STATUS_WASHING          => 'Sedang Dicuci',
            self::STATUS_DRYING           => 'Sedang Dikeringkan',
            self::STATUS_IRONING          => 'Sedang Disetrika/Dilipat',
            self::STATUS_READY            => 'Siap Diambil/Diantar',
            self::STATUS_DELIVERING       => 'Sedang Diantar',
            self::STATUS_COMPLETED        => 'Selesai',
            self::STATUS_CANCELLED        => 'Dibatalkan',
            self::STATUS_REJECTED         => 'Ditolak',
        ];

        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_AWAITING_PAYMENT => 'bg-amber-100 text-amber-800 border-amber-200',
            self::STATUS_PENDING_PICKUP   => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_RECEIVED         => 'bg-purple-100 text-purple-800 border-purple-200',
            self::STATUS_WASHING          => 'bg-cyan-100 text-cyan-800 border-cyan-200',
            self::STATUS_DRYING           => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            self::STATUS_IRONING          => 'bg-teal-100 text-teal-800 border-teal-200',
            self::STATUS_READY            => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::STATUS_DELIVERING       => 'bg-sky-100 text-sky-800 border-sky-200',
            self::STATUS_COMPLETED        => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_CANCELLED        => 'bg-rose-100 text-rose-800 border-rose-200',
            self::STATUS_REJECTED         => 'bg-gray-100 text-gray-800 border-gray-200',
            default                       => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
