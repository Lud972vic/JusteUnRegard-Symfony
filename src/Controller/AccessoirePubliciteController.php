<?php

namespace App\Controller;

use App\Entity\AccessoirePublicite;
use App\Form\AccessoirePubliciteType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/accessoirepublicite")
 */
class AccessoirePubliciteController extends AbstractController
{
    /**
     * @Route("/", name="accessoire_publicite_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /*On récupère l'utilisateur connecté, afin de filtré sur ses médias*/
        $userId = ($this->getUser()->getId());

        /*On récupère la liste de tous les medias*/
        $data = $this->getDoctrine()->getRepository(AccessoirePublicite::class)->findAccessoirePubliciteByIdUser($userId);

        /*Numéro de la page en cours, 1 par defaut (page 1), 3 nombre d'élements par page*/
        $accessoirespublicites = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), 3
        );

        return $this->render('accessoire_publicite/index.html.twig', [
            'accessoirespublicites' => $accessoirespublicites,
        ]);
    }

    /**
     * @Route("/new", name="accessoirepublicite_new", methods={"GET","POST"})
     */
    function new (Request $request, TranslatorInterface $translator): Response {
        $accessoirepublicite = new AccessoirePublicite();

        $form = $this->createForm(AccessoirePubliciteType::class, $accessoirepublicite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*On vérifie qu'un média est bien présent*/
            if (!$accessoirepublicite->getImageFile()) {
                /*Message flash*/
                $message = $translator->trans('Please select a medium, please.');
                $this->addFlash('message', $message);
            } else {
                /*On récupère l'utilisateur connecté et on le sauvegarde pour le média*/
                $accessoirepublicite->setUser($this->getUser());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($accessoirepublicite);
                $entityManager->flush();

                /*Message flash*/
                $message = $translator->trans('Your media has been published !');
                $this->addFlash('message', $message);

                /*Retour à l'accueil*/
                return $this->redirectToRoute('accessoire_publicite_index');
            }
        }

        return $this->render('accessoire_publicite/new.html.twig', [
            "form_title" => "Create un Accessoire & Publicité",
            'accessoirepublicite' => $accessoirepublicite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="accessoirepublicite_show", methods={"GET"})
     */
    public function show(AccessoirePublicite $accessoirepublicite): Response
    {
        return $this->render('accessoire_publicite/show.html.twig', [
            "form_title" => "Accessoire & Publicité",
            'accessoirepublicite' => $accessoirepublicite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="accessoirepublicite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AccessoirePublicite $accessoirepublicite, TranslatorInterface $translator): Response
    {/*On récupère le nom du fichier du média en BdD*/
        $unAccessoirePublicite = $accessoirepublicite->getNom();

        $form = $this->createForm(AccessoirePubliciteType::class, $accessoirepublicite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*On met à jour la date de modification*/
            $accessoirepublicite->setUpdatedAt(new \Datetime());
            $accessoirepublicite->setNom($unAccessoirePublicite);
            $this->getDoctrine()->getManager()->flush();

            /*Message flash*/
            $message = $translator->trans('Your media has been updated !');
            $this->addFlash('message', $message);

            return $this->redirectToRoute('accessoire_publicite_index');
        }

        return $this->render('accessoire_publicite/edit.html.twig', [
            "form_title" => "Edit Accessoire & Publicité",
            'accessoirepublicite' => $accessoirepublicite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="accessoirepublicite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AccessoirePublicite $accessoirepublicite, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $accessoirepublicite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessoirepublicite);
            $entityManager->flush();

            /*Message flash*/
            $message = $translator->trans('Your media has been deleted !');
            $this->addFlash('message', $message);
        }

        return $this->redirectToRoute('accessoire_publicite_index');
    }
}
