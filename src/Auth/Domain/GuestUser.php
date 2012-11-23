<?php

namespace Ctrl\Module\Auth\Domain;

use Doctrine\ORM\Mapping as ORM;

class GuestUser extends User
{
    public function isGuestUser()
    {
        return true;
    }
}