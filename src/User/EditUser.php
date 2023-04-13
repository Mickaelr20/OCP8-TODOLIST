<?php

namespace App\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class EditUser implements EditUserInterface
{
	public function __construct(private UserRepository $repo, private UserPasswordHasherInterface $passwordHasher)
	{
	}

	public function __invoke(User $user): void
	{
		$password = $this->passwordHasher->hashPassword($user, $user->getPassword());
		$user->setPassword($password);
		$this->repo->save($user);
	}
}
