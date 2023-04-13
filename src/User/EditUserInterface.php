<?php

namespace App\User;

use App\Entity\User;

interface EditUserInterface
{
	public function __invoke(User $user): void;
}
