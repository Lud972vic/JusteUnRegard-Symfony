<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleAddFromType;
use App\Form\CommentFormType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//Pour filtrer l'accès à cette route en fonction des droits des utilisateurs
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ArticleController
 * @package App\Controller
 * @Route("/news",name="news_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/articles-list", name="articles_list")
     */
    public function articlesList(Request $request, PaginatorInterface $paginator)
    {
        /*On récupère la liste de tous les articles*/
        $data = $this->getDoctrine()->getRepository(Article::class)->findBy(['visible' => '1'], ['created_at' => 'desc']);

        /*Numéro de la page en cours, 1 par defaut (page 1), 6 nombre d'élements par page*/
        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), 6
        );

        /*Savoir si un utilisateur est connecté et récupèrer ses informations pour un message*/
        $user = null;

        if ($this->isGranted('ROLE_USER') == true) {
            $user = explode('@', $this->getUser()->getUsername());
            $user = 'Hello ' . $user[0] . ', become an article editor on our blog.';
        } else {
            $user = 'Hello, subscribe and become an article editor on our blog.';
        }

        return $this->render('article/articlesList.html.twig', [
            'articles' => $articles,
            'user' => $user,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/article-add", name="article_add")
     */
    public function articleAdd(Request $request, TranslatorInterface $translator)
    {
        $articles = new Article();
        $form = $this->createForm(ArticleAddFromType::class, $articles);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*On récupère l'utilisateur connecté et on le sauvegarde pour l'articles*/
            $articles->setUser($this->getUser());
            /*On instancies Doctrine*/
            $doctrine = $this->getDoctrine()->getManager();
            /*On hydrate $commentaire*/
            $doctrine->persist($articles);
            /*On écrit dans la base de données*/
            $doctrine->flush();

            /*Message flash*/
            $message = $translator->trans('Your article has been published !');
            $this->addFlash('message', $message);

            /*Retour à l'accueil*/
            return $this->redirectToRoute('news_articles_list');
        }

        return $this->render('article/articleAdd.html.twig', [
            'articleAddFromType' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article-slug/{slug}", name="article_slug")
     */
    public function articleSlug($slug, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy([
            'slug' => $slug,
        ]);

        if (!$article) {
            throw $this->createNotFoundException("The requested article does not exist !");
        }

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
            $comments->setArticle($article);
            $comments->setCreatedAt(new \DateTime('now'));
            /*On instancies Doctrine*/
            $doctrine = $this->getDoctrine()->getManager();
            /*On hydrate $commentaire*/
            $doctrine->persist($comments);
            /*On écrit dans la base de données*/
            $doctrine->flush();
        }

        return $this->render('article/articleSlug.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'commentaireForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index", name="article_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /*On récupère l'utilisateur connecté, afin de filtrer sur ses articles*/
        $userId = ($this->getUser()->getId());

        /*On récupère la liste de tous ses articles*/
        $data = $this->getDoctrine()->getRepository(Article::class)->findArticleByIdUser($userId);

        /*Numéro de la page en cours, 1 par defaut (page 1), 3 nombre d'élements par page*/
        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), 3
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            "form_title" => "Article",
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article, TranslatorInterface $translator): Response
    {
        /*On récupère le nom du fichier du média en BdD*/
        $image = $article->getImage();

        $form = $this->createForm(ArticleAddFromType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*On met à jour la date de modification*/
            $article->setUpdatedAt(new \Datetime());
            $article->setImage($image);
            $this->getDoctrine()->getManager()->flush();

            /*Message flash*/
            $message = $translator->trans('Your media has been updated !');
            $this->addFlash('message', $message);

            return $this->redirectToRoute('news_article_index');
        }

        return $this->render('article/edit.html.twig', [
            "form_title" => "Edit article",
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            /*Message flash*/
            $message = $translator->trans('Your media has been deleted !');
            $this->addFlash('message', $message);
        }

        return $this->redirectToRoute('news_article_index');
    }
}
