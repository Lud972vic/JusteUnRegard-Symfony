<?php

namespace App\Controller;

use App\Entity\AccessoirePublicite;
use App\Entity\Commentaire;
use App\Entity\Media;
use App\Entity\Recherche;
use App\Form\CommentFormType;
use App\Form\ContactType;
use App\Form\InfoContactType;
use App\Form\RechercheType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/home", name="home_")
 */
class MainController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="home", methods={"GET","POST"})
     */
    public function mainIndex(Request $request, PaginatorInterface $paginator)
    {
        /*On récupère la liste de tous les medias*/
        //$data = $this->getDoctrine()->getRepository(Media::class)->findBy([], ['created_at' => 'desc']);
        $data = $this->getDoctrine()->getRepository(Media::class)->searchMedia('image/jpeg', 12);

        $dataAccessoire = $this->getDoctrine()->getRepository(AccessoirePublicite::class)->searchAllAccessoirePublicite('2');
        $dataPublicite = $this->getDoctrine()->getRepository(AccessoirePublicite::class)->searchAllAccessoirePublicite('1');

        /*Numéro de la page en cours, 1 par defaut (page 1), 12 nombre d'élements par page*/
        $medias = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        //Moteur de recherche
        $recherche = new Recherche();
        $form = $this->createForm(RechercheType::class, $recherche);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupère le mot clè tapé dans le formulaire
            //dd($request->query->get('search'));
            $mot = $recherche->getMotcle();
            if ($mot != "") {
                //Si on a fourni un mot clè on affiche tous les média contenant ce mot clè
                $medias = $this->getDoctrine()->getRepository(Media::class)->findAllMedia($mot);

                //dd($medias);
                return $this->render('main/mainRecherche.html.twig', [
                    'medias' => $medias,
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('main/mainIndex.html.twig', [
            'form' => $form->createView(),
            'medias' => $medias,
            'publicites' => $dataPublicite,
            'accessoires' => $dataAccessoire,
        ]);
    }

    /**
     * @Route("/murdephotographie", name="murdephotographie", methods={"GET"})
     */
    public function murDePhotographie(Request $request, PaginatorInterface $paginator)
    {
        /*On récupère la liste de tous les medias selon le type*/
        $data = $this->getDoctrine()->getRepository(Media::class)->searchAllMedia('image/jpeg');

        /*Numéro de la page en cours, 1 par defaut (page 1), 12 nombre d'élements par page*/
        $medias = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('main/mainMurDePhotographie.html.twig', [
            'medias' => $medias,
        ]);
    }

    /**
     * @Route("/murdephotographie/{id}", name="photographiezoom")
     */
    public function photographiezoom($id, Request $request)
    {
        $photographie = $this->getDoctrine()->getRepository(Media::class)->findOneBy([
            'id' => $id,
        ]);

        /*On instancie l'entité Commentaires*/
        $comments = new Commentaire();

        /*On crée l'object formulaire*/
        $form = $this->createForm(CommentFormType::class, $comments);

        /*On récupère les données saisies au Submit*/
        $form->handleRequest($request);
        /*On vérifie si le formulaire a été envoyé et si les données sont valides*/
        if ($form->isSubmitted() && $form->isValid()) {
            /*On récupère l'utilisateur connecté et on le sauvegarde pour le commentaire*/
            $comments->setUser($this->getUser());
            /*Ici le formulaire a été envoyé et les données sont valides
            On passe l'object $article pour joindre le commentaire à l'articke*/
            $comments->setMedia($photographie);
            $comments->setCreatedAt(new \DateTime('now'));
            /*On instancies Doctrine*/
            $doctrine = $this->getDoctrine()->getManager();
            /*On hydrate $commentaire*/
            $doctrine->persist($comments);
            /*On écrit dans la base de données*/
            $doctrine->flush();
        }

        return $this->render('main/mainPhotographieZoom.html.twig', [
            'photographie' => $photographie,
            'comments' => $comments,
            'commentaireForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/murdetutoriel", name="murdetutoriel", methods={"GET"})
     */
    public function murDeTutoriel(Request $request, PaginatorInterface $paginator)
    {
        /*On récupère la liste de tous les medias selon le type*/
        $data = $this->getDoctrine()->getRepository(Media::class)->searchAllMedia('video/webm', 'video/mp4');

        /*Numéro de la page en cours, 1 par defaut (page 1), 12 nombre d'élements par page*/
        $medias = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('main/mainMurDeTutoriel.html.twig', [
            'medias' => $medias,
        ]);
    }

    /**
     * @Route("/murdepublicite", name="murdepublicite", methods={"GET"})
     */
    public function murDePublicite(Request $request, PaginatorInterface $paginator)
    {
        /*On récupère la liste de tous les medias selon le type*/
        $data = $this->getDoctrine()->getRepository(AccessoirePublicite::class)->searchAllAccessoirePublicite('1');

        /*Numéro de la page en cours, 1 par defaut (page 1), 12 nombre d'élements par page*/
        $publicites = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('main/mainMurDePublicites.html.twig', [
            'publicites' => $publicites,
        ]);
    }

    /**
     * @Route("/murdevente", name="murdevente", methods={"GET","POST"})
     */
    public function murDeVente(Request $request, PaginatorInterface $paginator, MailerInterface $mailer)
    {
        /*On récupère la liste de tous les medias selon le type*/
        $data = $this->getDoctrine()->getRepository(AccessoirePublicite::class)->searchAllAccessoirePublicite('2');

        /*Numéro de la page en cours, 1 par defaut (page 1), 12 nombre d'élements par page*/
        $ventes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('main/mainMurDeVentes.html.twig', [
            'ventes' => $ventes,
        ]);
    }

    /**
     * @Route("/accessoirepublicite/{id}", name="accessoirepublicitezoom")
     */
    public function accessoirepublicitezoom($id, Request $request, MailerInterface $mailer)
    {
        $accessoirepublicite = $this->getDoctrine()->getRepository(AccessoirePublicite::class)->findOneBy([
            'id' => $id,
        ]);

        //Formulaire de contact
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On crée le mail
            $email = (new TemplatedEmail())
            //Email de l'utilisateur connecté
            ->from($this->security->getUser()->getEmail())
                ->to($accessoirepublicite->getUser()->getEmail())
                ->subject('Contact au sujet du matériel')
                ->htmlTemplate('emails/contact_accessoire_publicite.html.twig')
                ->context([
                    'accessoirepublicite' => $accessoirepublicite,
                    'mail' => $contact->get('email')->getData(),
                    'message' => $contact->get('message')->getData(),
                    'image' => $request->getSchemeAndHttpHost() . '/uploads/images/featured/' . $accessoirepublicite->getNom(),
                    'imageFooter' => $request->getSchemeAndHttpHost() . '/uploads/email/work-4997565.png',
                    'url' => $request->getSchemeAndHttpHost() . $request->getPathInfo(),
                ]);

            //On envoie le mail
            $mailer->send($email);

            //On confirme et on redirige
            $this->addFlash('message', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('home_murdevente');
        }

        return $this->render('main/mainAccessoirePubliciteZoom.html.twig', [
            'accessoirepublicite' => $accessoirepublicite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/legal-notices",name="legal_notices" )
     */
    public function legalNotices()
    {
        return $this->render('main/mainLegalNotices.html.twig');
    }

    /**
     * @Route("/notre_equipe",name="notre_equipe" )
     */
    public function notreEquipe()
    {
        return $this->render('main/mainNotreEquipe.html.twig');
    }

    /**
     * @Route("/nous_contacter",name="nous_contacter" )
     */
    public function nousContacter(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(InfoContactType::class);
        $contact = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On crée le mail
            $email = (new TemplatedEmail())
            //Email de l'internaute
            ->from($contact->get('email')->getData())
                ->to('ludovic@jur.fr')
                ->subject('Contact depuis le site JusteUnRegard')
                ->htmlTemplate('emails/infocontact.html.twig')
                ->context([
                    'mail' => $contact->get('email')->getData(),
                    'sujet' => $contact->get('sujet')->getData(),
                    'message' => $contact->get('message')->getData(),
                    'imageFooter' => $request->getSchemeAndHttpHost() . '/uploads/email/work-4997565.png',
                ]);

            //On envoie le mail
            $mailer->send($email);

            //On confirme et on redirige
            $this->addFlash('message', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('home_nous_contacter');
        }

        return $this->render('main/mainNousContacter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/change-locale/{locale}",name="change_locale" )
     */
    public function changeLocale($locale, Request $request)
    {
        //On stocke la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);

        //On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
