<?php

namespace App\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;

final class EditTask implements EditTaskInterface
{
	public function __construct(private TaskRepository $repo)
	{
	}

	public function __invoke(Task $task): void
	{
		$this->repo->save($task);
	}
}
