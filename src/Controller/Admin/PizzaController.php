<?php

namespace App\Controller\Admin;

use App\Entity\Pizza;
use App\Form\PizzaType;
use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PhotoService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/pizza')]
class PizzaController extends AbstractController
{
    #[Route('/', name: 'app_admin_pizza_index', methods: ['GET'])]
    public function index(PizzaRepository $pizzaRepository): Response
    {
        return $this->render('admin/pizza/index.html.twig', [
            'pizzas' => $pizzaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_pizza_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PizzaRepository $pizzaRepository, PhotoService $photoService): Response
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileName = $photoService->upload($photoFile);
                $pizza->setPhoto($photoFileName);
            }
            $pizzaRepository->save($pizza, true);

            return $this->redirectToRoute('app_admin_pizza_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/pizza/new.html.twig', [
            'pizza' => $pizza,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_pizza_show', methods: ['GET'])]
    public function show(Pizza $pizza): Response
    {
        return $this->render('admin/pizza/show.html.twig', [
            'pizza' => $pizza,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_pizza_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pizza $pizza, PizzaRepository $pizzaRepository, PhotoService $photoService): Response
    {
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileName = $photoService->upload($photoFile);
                $pizza->setPhoto($photoFileName);
            }
            $pizzaRepository->save($pizza, true);

            return $this->redirectToRoute('app_admin_pizza_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/pizza/edit.html.twig', [
            'pizza' => $pizza,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_pizza_delete', methods: ['POST'])]
    public function delete(Request $request, Pizza $pizza, PizzaRepository $pizzaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizza->getId(), $request->request->get('_token'))) {
            $pizzaRepository->remove($pizza, true);
        }

        return $this->redirectToRoute('app_admin_pizza_index', [], Response::HTTP_SEE_OTHER);
    }
}
