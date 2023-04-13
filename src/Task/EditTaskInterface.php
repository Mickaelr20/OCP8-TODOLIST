<?php

namespace App\Task;

use App\Entity\Task;

interface EditTaskInterface
{
	public function __invoke(Task $task): void;
}
