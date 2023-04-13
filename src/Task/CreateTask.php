<?php

namespace App\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;

final class CreateTask implements CreateTaskInterface
{
	public function __construct(private TaskRepository $repo)
	{
	}

	public function __invoke(Task $task, User $user): void
	{
		$task->setUser($user);
		$this->repo->save($task);
	}
}
