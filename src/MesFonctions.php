<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MesFonctions
{
    public static function verificationMotDePasse($password, $confirm_password)
    {
        if ( /*On vérifie que les mots de passse sont identiques*/
            ($password == $confirm_password)
            &&
            /*On verifie que les valeurs sont différentes de null*/
            ($password !== null && $confirm_password !== null)
            &&
            /*On vérifie le nombre de caractere du nouveau mot de passe*/
            (strlen($password) >= 8 && strlen($password) <= 50)
        ) {
            $new_password = 'MdpOK';
        } else {
            $new_password = 'MdpKo';
        }
        return ($new_password);
    }

    public static function supprimerMedia($chemin, $media)
    {
        $leMedia = public_path() . $chemin . $media;

        if (file_exists($leMedia)) {
            unlink($leMedia);
            echo "The media is deleted.";
        } else {
            echo "The media does not exist.";
        }
    }

    /**
     * @param $leRepository
     * @param $laMethode
     * @return Response
     */
    public static function apiGet($leRepository, $laMethode, $id = null)
    /*
     * On passe en parametre de la fonction la dépendence ArticleRepository, cela revient au même si on
     * $this->getDoctrine()->getRepository(Article::class)...
     */
    {
        //On récupere la liste des articles. On créee la méthode apiFindAll pour éviter d'exposer des colonnes sensibles, comme le mdp...
        $articles = $leRepository->$laMethode($id);

        //Normaliser, on spécifié qu'on utilise un encodeur en JSON
        $encoders = [new JsonEncoder()];

        //On instancie le normaliseur pour convertir la collection en un tableau
        $normalizers = [new ObjectNormalizer()];

        /*
         * On fait la conversion en JSON
         * On instancie le convertisseur
         */
        $serialiser = new Serializer($normalizers, $encoders);

        //On convertie en JSON. Pour éviter le message "A circular reference has been detected when serializing the object of class"
        $jsonContent = $serialiser->serialize($articles, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);

        //On instancie la réponse
        $reponse = new Response($jsonContent);

        //On ajoute l'entête HTTP
        $reponse->headers->set('Content-Type', 'application/json');

        //On envoie la réponse
        return $reponse;
    }
}
