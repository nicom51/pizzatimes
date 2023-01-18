<?php

namespace App\Controller\Client;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AllergieController extends AbstractController
{
/*    #[Route('/client/allergie', name: 'app_client_allergie')]
    public function index(): Response
    {
        return $this->render('client/allergie/index.html.twig', [
            'controller_name' => 'AllergieController',
        ]);
    }*/

    #[Route('client/allergie', name: 'app_client_allergie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/allergie/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
}
