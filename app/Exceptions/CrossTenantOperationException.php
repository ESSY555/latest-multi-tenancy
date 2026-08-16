<?php

namespace App\Exceptions;

use Exception;

class CrossTenantOperationException extends Exception
{
    public function __construct($message = "Attempt to perform a cross-tenant operation without explicit authorization.")
    {
        parent::__construct($message);
    }
}
