<?php

namespace GestionArticleBundle\Controller;

use GestionArticleBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * Récupération de tous les articles existants
     *
     * @Route("/api/article")
     * @Method("GET")
     */
    public function getAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('GestionArticleBundle:Article')->findAll();

        $results = array();
        foreach ($articles as $article) {
            $auteurs = array();

            foreach ($article->getAuteurs() as $auteur) {
                $auteurs[] = array(
                    'id' => $auteur->getId(),
                    'firstname' => $auteur->getFirstname(),
                    'lastname' => $auteur->getLastname(),
                    'email' => $auteur->getEmail()
                );
            }

            $results[] = array(
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'date' => $article->getDate(),
                'text' => $article->getText(),
                'authors' => $auteurs
            );
        }

        return new JsonResponse($results);
    }

    /**
     * Récupération d'un article spécifique suivant son identifiant
     *
     * @Route("/api/article/{id}")
     * @Method("GET")
     */
    public function getArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('GestionArticleBundle:Article')->findOneById($id);

        $result = array();

        if ($article !== null) {
            $auteurs = array();

            foreach ($article->getAuteurs() as $auteur) {
                $auteurs[] = array(
                    'id' => $auteur->getId(),
                    'firstname' => $auteur->getFirstname(),
                    'lastname' => $auteur->getLastname(),
                    'email' => $auteur->getEmail()
                );
            }

            $result = array(
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'date' => $article->getDate(),
                'text' => $article->getText(),
                'authors' => $auteurs
            );
        }

        return new JsonResponse($result);
    }

    /**
     * Ajout d'un article
     *
     * @Route("/api/article")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $title = $request->get('title');
        $date = $request->get('date');
        $text = $request->get('text');
        $authors = explode(',', $request->get('authors'));
        
        $article = new Article();
        $article
            ->setTitle($title)
            ->setDate($date)
            ->setText($text);

        // Ajout des auteurs à l'article
        $em = $this->getDoctrine()->getManager();
        foreach ($authors as $author_id) {
            $auteur = $em->getRepository('GestionArticleBundle:Auteur')->findOneById($author_id);

            if ($auteur) {
                $article->addAuteur($auteur);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Modification d'un article
     *
     * @Route("/api/article")
     * @Method("PUT")
     */
    public function putAction(Request $request)
    {
        $id = $request->get('id');
        $title = $request->get('title');
        $date = $request->get('date');
        $text = $request->get('text');
        $authors = explode(',', $request->get('authors'));

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('GestionArticleBundle:Article')->findOneById($id);
        $article
            ->setTitle($title)
            ->setDate($date)
            ->setText($text);

        // Retire tous les auteurs liés à l'article
        foreach ($article->getAuteurs() as $author) {
            $article->removeAuteur($author);
        }

        // Ajout les nouveaux auteurs à l'article
        $em = $this->getDoctrine()->getManager();
        foreach ($authors as $author_id) {
            $auteur = $em->getRepository('GestionArticleBundle:Auteur')->findOneById($author_id);

            if ($auteur) {
                $article->addAuteur($auteur);
            }
        }

        // Enregistre les modifications
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Suppression d'un article
     *
     * @Route("/api/article/{id}")
     * @Method("DELETE")
     */
    public function delAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('GestionArticleBundle:Article')->findOneById($id);

        $em->remove($article);
        $em->flush();

        return new JsonResponse();
    }
}
