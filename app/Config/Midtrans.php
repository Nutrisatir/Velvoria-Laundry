<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey = '';
    public string $clientKey = '';
    public bool $isProduction = false;
    public bool $isSanitized = true;
    public bool $is3ds = true;

    public function __construct()
    {
        parent::__construct();

        // Support both the existing dotted CI4 .env names and conventional
        // uppercase names. Never provide a credential fallback in source code.
        $this->serverKey = trim((string) env('midtrans.serverKey', env('MIDTRANS_SERVER_KEY', '')));
        $this->clientKey = trim((string) env('midtrans.clientKey', env('MIDTRANS_CLIENT_KEY', '')));
        $this->isProduction = filter_var(
            env('midtrans.isProduction', env('MIDTRANS_IS_PRODUCTION', false)),
            FILTER_VALIDATE_BOOLEAN
        );
        $this->isSanitized = filter_var(
            env('midtrans.isSanitized', env('MIDTRANS_IS_SANITIZED', true)),
            FILTER_VALIDATE_BOOLEAN
        );
        $this->is3ds = filter_var(
            env('midtrans.is3ds', env('MIDTRANS_IS_3DS', true)),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}
