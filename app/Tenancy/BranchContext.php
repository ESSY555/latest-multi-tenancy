<?php

namespace App\Tenancy;

use App\Exceptions\TenantContextMissingException;

class BranchContext
{
    protected static ?int $branchId = null;
    protected static bool $bypassed = false;

    /**
     * Explicitly set the branch context (useful for jobs or console commands).
     */
    public static function setBranchId(int $branchId): void
    {
        self::$branchId = $branchId;
    }

    /**
     * Clear the programmatic branch context and bypass state.
     */
    public static function clear(): void
    {
        self::$branchId = null;
        self::$bypassed = false;
    }

    /**
     * Set whether the branch scope should be bypassed for system-wide operations.
     */
    public static function bypass(bool $bypass = true): void
    {
        self::$bypassed = $bypass;
    }

    /**
     * Check if the branch scope is bypassed.
     */
    public static function isBypassed(): bool
    {
        return self::$bypassed;
    }

    /**
     * Retrieve the current active branch ID.
     * Checks explicit programmatic context first, then falls back to session.
     * Throws an exception if no context is found.
     */
    public static function getBranchId(): int
    {
        if (self::$bypassed) {
            throw new \RuntimeException("Cannot get branch ID when tenancy is bypassed.");
        }

        // 1. Explicit programmatic context (jobs/console)
        if (self::$branchId !== null) {
            return self::$branchId;
        }

        // 2. Web session context
        if (app()->bound('session') && app('session')->has('current_branch_id')) {
            return (int) app('session')->get('current_branch_id');
        }

        // No context found -> fail safely
        throw new TenantContextMissingException();
    }

    /**
     * Safely check if a branch context exists without throwing an exception.
     */
    public static function hasBranch(): bool
    {
        if (self::$bypassed) {
            return false;
        }
        
        if (self::$branchId !== null) {
            return true;
        }
        
        if (app()->bound('session') && app('session')->has('current_branch_id')) {
            return true;
        }
        
        return false;
    }
}
