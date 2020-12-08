<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Media;
use App\Entity\MediaLike;
use App\Form\MediaType;
use App\Repository\MediaLikeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/media")
 */
class MediaController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/", name="media_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /*On récupère l'utilisateur connecté, afin de filtrer sur ses médias*/
        $userId = ($this->getUser()->getId());

        /*On récupère la liste de tous les medias*/
        //$data = $this->getDoctrine()->getRepository(Media::class)->findBy([], ['created_at' => 'desc']);
        $data = $this->getDoctrine()->getRepository(Media::class)->findMediaByIdUser($userId);

        /*Numéro de la page en cours, 1 par defaut (page 1), 3 nombre d'élements par page*/
        $medias = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), 3
        );

        return $this->render('media/index.html.twig', [
            'media' => $medias,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new", name="media_new", methods={"GET","POST"})
     */
    function new (Request $request, TranslatorInterface $translator): Response {
        $medium = new Media();

        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*On vérifie qu'un média est bien présent*/
            if (!$medium->getImageFile()) {
                /*Message flash*/
                $message = $translator->trans('Please select a medium, please.');
                $this->addFlash('message', $message);
            } else {
                /*On récupère l'utilisateur connecté et on le sauvegarde pour le média*/
                $medium->setUser($this->getUser());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($medium);
                $entityManager->flush();

                /*Message flash*/
                $message = $translator->trans('Your media has been published !');
                $this->addFlash('message', $message);

                /*Retour à l'accueil*/
                return $this->redirectToRoute('media_index');
            }
        }

        return $this->render('media/new.html.twig', [
            "form_title" => "Create new Media",
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="media_show", methods={"GET"})
     */
    public function show(Media $medium): Response
    {
        // $test = $this->getDoctrine()->getRepository(Commentaire::class)->findCommentByMedia($medium);
        // dd($test);

        return $this->render('media/show.html.twig', [
            "form_title" => "Media",
            'medium' => $medium,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/edit", name="media_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $medium, TranslatorInterface $translator): Response
    {/*On récupère le nom du fichier du média en BdD*/
        $leMedium = $medium->getNom();

        $form = $this->createForm(MediaType::class, $medium);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*On met à jour la date de modification*/
            $medium->setUpdatedAt(new \Datetime());
            $medium->setNom($leMedium);
            $this->getDoctrine()->getManager()->flush();

            /*Message flash*/
            $message = $translator->trans('Your media has been updated !');
            $this->addFlash('message', $message);

            return $this->redirectToRoute('media_index');
        }

        return $this->render('media/edit.html.twig', [
            "form_title" => "Edit media",
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="media_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Media $medium, MediaLikeRepository $mediaLikeRepository, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $medium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medium);
            $entityManager->flush();

            /*Message flash*/
            $message = $translator->trans('Your media has been deleted !');
            $this->addFlash('message', $message);
        }

        return $this->redirectToRoute('media_index');
    }

    /**
     * Permet de liker ou unliker un média
     *
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/likeunlike", name="media_likeunlike")
     *
     * @param Media $medium
     * @param ObjectManager $objectManager
     * @param MediaLikeRepository $mediaLikeRepository
     * @return Response
     */
    public function likeUnlike(Media $medium, MediaLikeRepository $mediaLikeRepository): Response
    {

        //ObjectManager $objectManager

        $user = $this->getUser();

        /*Si l'utilisateur n'est pas connecté*/
        if (!$user) {
            return $this->json(
                ['code' => 403,
                    'message' => 'Unauthorized']
                , 403
            );
        }

        /*Si l'utilisateur aime déjà ce media, donc si je clique j'inverse à "je n'aime plus" le média */
        if ($medium->isLikedByUser($user)) {
            $like = $mediaLikeRepository->findOneBy([
                'media' => $medium,
                'user' => $user,
            ]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($like);
            $entityManager->flush();

            return $this->json(
                ['code' => 200,
                    'message' => 'Like deleted',
                    'likes' => $mediaLikeRepository->count(['media' => $medium]),
                ]
                , 200
            );
        }

        $like = new MediaLike();
        $like->setMedia($medium)
            ->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($like);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like added',
            'likes' => $mediaLikeRepository->count(['media' => $medium]),
        ], 200);
    }

    /**
     * Commentaire en Ajax
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/ajaxcommentaire/{id}", name="ajaxaddcomment", methods={"GET","POST"})
     */
    public function ajaxComment(Media $medium, Request $request): Response
    {
        if ($request->isXMLHttpRequest()) {

            $content = $request->getContent();

            if (!empty($content)) {
                $params = json_decode($content, true);
                $message = trim($params['message']);

                if (!empty($message)) {
                    $user = $this->getUser();

                    $ajaxCommentaire = new Commentaire();

                    $ajaxCommentaire
                        ->setMedia($medium)
                        ->setUser($user)
                        ->setRgpd(true)
                        ->setContenu($message);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($ajaxCommentaire);
                    $entityManager->flush();

                    /*On récupère la liste de tous les commentaires du média*/
                    $data = $this->getDoctrine()->getRepository(Commentaire::class)->findCommentByMedia($medium);

                    return $this->json([
                        'code' => 200,
                        'status' => 'Commentaire en ajax ajouté dans la BdD',
                        //'listComments' => json_encode($medium->getId())
                        'listComments' => json_encode($data),
                    ], 200);
                } else {
                    // return $this->json([
                    //     'code' => 400,
                    //     'status' => 'Le message est vide, en bloque l\'insertion en BdD',
                    // ], 400);

                    $data = $this->getDoctrine()->getRepository(Commentaire::class)->findCommentByMedia($medium);

                    return $this->json([
                        'code' => 200,
                        'status' => 'Le message est vide, en bloque l\'insertion en BdD et on renvois la liste des anciens messages',
                        'listComments' => json_encode($data),
                    ], 200);
                }
            }
        }
    }

    /**
     * Permet de bannir un média
     * 
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/banni", name="media_banni")
     * @param Media $medium
     * @return Response
     */
    public function bannir(Media $medium): Response
    {
        /*Si l'utilisateur n'est pas connecté*/
        //$user = $this->getUser(); //email par défaut
        $user = $this->getUser()->getPrenom();

        if (!$user) {
            return $this->json(
                ['code' => 403,
                    'message' => 'Unauthorized']
                , 403
            );
        }

        /*N'importe qui, peut participer au bannissement actif de média qui ne respecte pas la charte du site*/
        $medium->setBanni(true)
            ->setBanniParUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($medium);
        $entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Media banni',
        ], 200);
    }

    /**
     * @Route("/{id}/media_show_user", name="media_show_user", methods={"GET"})
     */
    public function media_show_user(Request $request, PaginatorInterface $paginator): Response
    {
        /*On récupère l'utilisateur du média, afin d'afficher tous ses médias*/
        $userId = ($request->attributes->get('id'));

        /*On récupère la liste de tous les medias*/
        $medias = $this->getDoctrine()->getRepository(Media::class)->findMediaByIdUser($userId);

        return $this->render('main/mainUsetMurDePhotographie.html.twig', [
            'medias' => $medias,
            'nom' => $medias[0]->getUser()->getPseudo()
        ]);
    }

    /**
     * @Route("/{id}/media_show_category", name="media_show_category", methods={"GET"})
     */
    public function media_show_category(Request $request, PaginatorInterface $paginator): Response
    {
        /*On récupère l'id de la catégorie*/
        $cat = ($request->attributes->get('id'));

        /*On récupère la liste de tous les medias*/
        $medias = $this->getDoctrine()->getRepository(Media::class)->findMediaByIdCategory($cat);

        return $this->render('main/mainUsetMurDePhotographie.html.twig', [
            'medias' => $medias,
            'nom' => $medias[0]->getUser()->getPseudo()
        ]);
    }
}
