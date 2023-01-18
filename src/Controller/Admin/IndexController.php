<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/admin/index', name: 'app_admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
