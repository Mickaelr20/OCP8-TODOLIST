<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use App\User\CreateUserInterface;
use App\User\EditUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/users', name: 'user_')]
class UsersController extends AbstractController
{
	function __construct(private UserRepository $userRepository) {
		
    }

	#[Route('', name: 'list', methods: ['GET'])]
    public function list()
    {
        return $this->render('user/list.html.twig', ['users' => $this->userRepository->findAll()]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, CreateUserInterface $createUser)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUser($user);
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function editAction(User $user, Request $request, EditUserInterface $editUser)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editUser($user);
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
