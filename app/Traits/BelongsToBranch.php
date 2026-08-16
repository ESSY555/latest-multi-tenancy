<?php

namespace App\Traits;

use App\Models\Scopes\BranchScope;
use App\Tenancy\BranchContext;
use App\Exceptions\CrossTenantOperationException;

trait BelongsToBranch
{
    /**
     * Boot the BelongsToBranch trait for a model.
     */
    public static function bootBelongsToBranch()
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function ($model) {
            if (BranchContext::isBypassed()) {
                return;
            }

            $currentBranchId = BranchContext::getBranchId();

            if (isset($model->branch_id)) {
                if ((int)$model->branch_id !== $currentBranchId) {
                    throw new CrossTenantOperationException("Cannot create record for branch {$model->branch_id} while in branch {$currentBranchId} context.");
                }
            } else {
                $model->branch_id = $currentBranchId;
            }
        });

        static::updating(function ($model) {
            if (BranchContext::isBypassed()) {
                return;
            }
            
            // Prevent cross-tenant reassignment
            if ($model->isDirty('branch_id')) {
                $currentBranchId = BranchContext::getBranchId();
                if ((int)$model->branch_id !== $currentBranchId) {
                    throw new CrossTenantOperationException("Cannot move record to branch {$model->branch_id}. Cross-tenant reassignment is forbidden without explicit authorization.");
                }
            }
        });
    }

    /**
     * Relationship to the branch.
     */
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }
}
