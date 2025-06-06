<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public function logActivity(string $action, string $targetType = null, int $targetId = null, string $description = null)
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id'     => $user?->id,
            'role'        => $user?->getRoleNames()->first(),
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'description' => $description,
        ]);
    }
}
