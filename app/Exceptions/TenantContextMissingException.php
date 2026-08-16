<?php

namespace App\Exceptions;

use Exception;

class TenantContextMissingException extends Exception
{
    public function __construct($message = "Tenant branch context is missing for this operation.")
    {
        parent::__construct($message);
    }
}
