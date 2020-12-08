<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu", name="lieu")
     */
    public function index()
    {
        $listeUsers = $this->getDoctrine()->getRepository(User::class)->findPinBydUser();

        $markers = array();

        foreach ($listeUsers as $item) {
            $markers[] = [
                'name' => $item['pseudo'],
                'city' => $item['nom'],
                'profil' => $item['profil'] . '. Biographie (' . $item['description'] . ') ' . "<a href='mailto:$item[email]'><i class=\"fa fa-envelope prefix grey-text\"></i> Contacter-moi<a>",
                'lat' => $item['latitude'],
                'lng' => $item['longitude'],
            ];
        }
        $markers = json_encode($markers);
        //dd($markers);
        //$test  = $markers[0]['name'];
        
        return $this->render('lieu/index.html.twig', [
            'markers' => $markers,
        ]);
    }
}
