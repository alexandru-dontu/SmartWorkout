<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;

class AuthService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser()
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new \NotLoggedInException('You are not logged in.');
        }
        return $user;
    }
}
