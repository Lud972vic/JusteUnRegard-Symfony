<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TchatController extends AbstractController
{
    /**
     * @Route("/tchat", name="tchat")
     */
    public function index()
    {
        return $this->render('tchat/index.html.twig', [
            'controller_name' => 'TchatController',
        ]);
    }
}
