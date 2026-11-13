<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminActivityLogModel;

class ActivityLogController extends BaseController
{
    public function index()
    {
        $model = new AdminActivityLogModel();
        $logs = $model->select('admin_activity_logs.*, users.name AS user_name')
            ->join('users', 'users.id = admin_activity_logs.user_id', 'left')
            ->orderBy('admin_activity_logs.created_at', 'DESC')
            ->paginate(25);

        return view('admin/activity-logs/index', [
            'logs'  => $logs,
            'pager' => $model->pager,
        ]);
    }
}
