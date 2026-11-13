<?php

namespace App\Controllers\Payment;

use App\Controllers\BaseController;
use App\Services\MidtransService;

class MidtransNotificationController extends BaseController
{
    public function handle()
    {
        // Webhook handler - accepts JSON payload from Midtrans
        $json = $this->request->getJSON(true);

        if (empty($json)) {
            // Also check raw input in case JSON is sent as raw post body
            $rawInput = file_get_contents('php://input');
            $json     = json_decode($rawInput, true);
        }

        if (empty($json)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Empty payload received',
            ]);
        }

        $midtransService = new MidtransService();
        $result          = $midtransService->handleNotification($json);

        if (! $result['success']) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => $result['message'],
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'ok',
            'message' => $result['message'],
        ]);
    }
}
