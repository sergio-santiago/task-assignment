<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountInactiveException extends AccountStatusException
{
    public function getMessageKey()
    {
        return 'The account is inactive';
    }
}
