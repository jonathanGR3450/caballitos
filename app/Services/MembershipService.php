<?php

namespace App\Services;

use App\Models\User;
use App\Models\TipoListado;
use Carbon\Carbon;

class MembershipService
{
    public function assignPlan(User $user, TipoListado $plan, ?Carbon $start = null): void
    {
        $user->forceFill([
            'tipo_listado_id' => $plan->id,
            'membresia_comprada_en' => ($start ?? now())->toDateString(),
        ])->save();
    }

    public function canPublish(User $user): bool
    {
        return $user->canPublishProduct();
    }

    public function remaining(User $user): ?int
    {
        return $user->remainingQuota();
    }
}
