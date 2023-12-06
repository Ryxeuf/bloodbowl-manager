<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordProvider
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function hashPassword(string $plainPassword): string
    {
        return $this->hasher->hashPassword(new Users(), $plainPassword);
    }

}
