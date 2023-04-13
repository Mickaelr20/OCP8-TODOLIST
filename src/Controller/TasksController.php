<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Entity\Task;
use App\Form\TaskType;
use App\Task\CreateTaskInterface;
use App\Task\EditTaskInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/tasks', name: 'task_')]
class TasksController extends AbstractController
{
	function __construct(private TaskRepository $taskRepository) {
		
    }

	#[Route('', name: 'list', methods: ['GET'])]
	public function list()
	{
		return $this->render('task/list.html.twig', ['tasks' => $this->taskRepository->findAll()]);
	}

	#[Route('/create', name: 'create', methods: ['GET', 'POST'])]
	public function createAction(Request $request, CreateTaskInterface $createTask)
	{
		$task = new Task();
		$form = $this->createForm(TaskType::class, $task);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
            $createTask($task, $this->getUser());
			$this->addFlash('success', 'La tâche a été bien été ajoutée.');
			return $this->redirectToRoute('task_list');
		}

		return $this->render('task/create.html.twig', ['form' => $form->createView()]);
	}
	
	#[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function editAction(Task $task, Request $request, EditTaskInterface $editTask)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editTask($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

	#[Route('/{id}/toggle', name: 'toggle', methods: ['GET', 'POST'])]
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
		$this->taskRepository->save($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

	#[Route('/{id}/delete', name: 'delete', methods: ['GET', 'POST'])]
    public function deleteTaskAction(Task $task)
    {
        $this->taskRepository->remove($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
