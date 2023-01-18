<?php

namespace App\Controller\Client;

use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/client/index', name: 'app_client_index')]
    public function index(PizzaRepository $pizzaRepository): Response
    {
        return $this->render('client/index/index.html.twig', [
            'pizzas' => $pizzaRepository->findAll(),
        ]);
    }

    #[Route('/client/commande', name: 'app_client_order')]
    public function order(PizzaRepository $pizzaRepository): Response
    {
        return $this->render('client/index/order.html.twig');
    }
}
