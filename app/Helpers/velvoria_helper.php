<?php

if (! function_exists('format_rupiah')) {
    function format_rupiah(int $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}

if (! function_exists('format_date')) {
    function format_date(string $datetime): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $ts = strtotime($datetime);
        $day = (int) date('j', $ts);
        $month = $months[(int) date('n', $ts)];
        $year = date('Y', $ts);

        return "{$day} {$month} {$year}";
    }
}

if (! function_exists('format_datetime')) {
    function format_datetime(string $datetime): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $ts = strtotime($datetime);
        $day = (int) date('j', $ts);
        $month = $months[(int) date('n', $ts)];
        $year = date('Y', $ts);
        $time = date('H:i', $ts);

        return "{$day} {$month} {$year}, {$time}";
    }
}

if (! function_exists('order_status_label')) {
    function order_status_label(string $status): string
    {
        return match ($status) {
            'awaiting_payment' => 'Menunggu Pembayaran',
            'pending_pickup'   => 'Menunggu Penjemputan',
            'received'         => 'Diterima',
            'washing'          => 'Dicuci',
            'drying'           => 'Dikeringkan',
            'ironing'          => 'Disetrika',
            'ready'            => 'Siap Diambil',
            'delivering'       => 'Dikirim',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
            'rejected'         => 'Ditolak',
            default            => ucfirst(str_replace('_', ' ', $status)),
        };
    }
}

if (! function_exists('order_status_badge')) {
    function order_status_badge(string $status): string
    {
        $label = order_status_label($status);
        $classes = match ($status) {
            'awaiting_payment' => 'bg-yellow-100 text-yellow-800',
            'pending_pickup'   => 'bg-blue-100 text-blue-800',
            'received'         => 'bg-indigo-100 text-indigo-800',
            'washing'          => 'bg-cyan-100 text-cyan-800',
            'drying'           => 'bg-sky-100 text-sky-800',
            'ironing'          => 'bg-purple-100 text-purple-800',
            'ready'            => 'bg-emerald-100 text-emerald-800',
            'delivering'       => 'bg-orange-100 text-orange-800',
            'completed'        => 'bg-green-100 text-green-800',
            'cancelled'        => 'bg-red-100 text-red-800',
            'rejected'         => 'bg-rose-100 text-rose-800',
            default            => 'bg-gray-100 text-gray-800',
        };

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $classes . '">'
            . esc($label)
            . '</span>';
    }
}

if (! function_exists('payment_status_label')) {
    function payment_status_label(string $status): string
    {
        return match ($status) {
            'unpaid'     => 'Belum Dibayar',
            'pending'    => 'Menunggu',
            'paid'       => 'Lunas',
            'failed'     => 'Gagal',
            'expired'    => 'Kedaluwarsa',
            'refunded'   => 'Dikembalikan',
            'challenge'  => 'Challenge',
            default      => ucfirst($status),
        };
    }
}

if (! function_exists('payment_status_badge')) {
    function payment_status_badge(string $status): string
    {
        $label = payment_status_label($status);
        $classes = match ($status) {
            'unpaid'    => 'bg-gray-100 text-gray-800',
            'pending'   => 'bg-yellow-100 text-yellow-800',
            'paid'      => 'bg-green-100 text-green-800',
            'failed'    => 'bg-red-100 text-red-800',
            'expired'   => 'bg-orange-100 text-orange-800',
            'refunded'  => 'bg-purple-100 text-purple-800',
            'challenge' => 'bg-amber-100 text-amber-800',
            default     => 'bg-gray-100 text-gray-800',
        };

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $classes . '">'
            . esc($label)
            . '</span>';
    }
}

if (! function_exists('time_ago')) {
    function time_ago(string $datetime): string
    {
        $now = time();
        $ts = strtotime($datetime);
        $diff = $now - $ts;

        if ($diff < 0) {
            return 'baru saja';
        }

        if ($diff < 60) {
            return 'baru saja';
        }

        if ($diff < 3600) {
            $minutes = (int) floor($diff / 60);
            return "{$minutes} menit lalu";
        }

        if ($diff < 86400) {
            $hours = (int) floor($diff / 3600);
            return "{$hours} jam lalu";
        }

        if ($diff < 2592000) {
            $days = (int) floor($diff / 86400);
            return "{$days} hari lalu";
        }

        if ($diff < 31536000) {
            $months = (int) floor($diff / 2592000);
            return "{$months} bulan lalu";
        }

        $years = (int) floor($diff / 31536000);
        return "{$years} tahun lalu";
    }
}

if (! function_exists('active_nav')) {
    function active_nav(string $path): string
    {
        $currentPath = '/' . trim(uri_string(), '/');
        $checkPath = '/' . trim($path, '/');

        if ($currentPath === $checkPath) {
            return 'active';
        }

        if ($checkPath !== '/' && str_starts_with($currentPath, $checkPath)) {
            return 'active';
        }

        return '';
    }
}
