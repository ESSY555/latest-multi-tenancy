<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Tenancy\BranchContext;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        if (BranchContext::isBypassed()) {
            return;
        }

        // This will throw TenantContextMissingException if the context is missing,
        // fulfilling the requirement to fail loudly instead of silently returning 1=0.
        $branchId = BranchContext::getBranchId();

        $builder->where($model->getTable() . '.branch_id', $branchId);
    }
}
