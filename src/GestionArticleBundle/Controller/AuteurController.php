<?php

namespace GestionArticleBundle\Controller;

use GestionArticleBundle\Entity\Auteur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuteurController extends Controller
{
    /**
     * Récupération de tous les auteurs existants
     *
     * @Route("/api/auteur")
     * @Method("GET")
     */
    public function getAction()
    {
        $em = $this->getDoctrine()->getManager();
        $auteurs = $em->getRepository('GestionArticleBundle:Auteur')->findAll();

        $results = array();
        foreach ($auteurs as $auteur) {
            $results[] = array(
                'id' => $auteur->getId(),
                'firstname' => $auteur->getFirstname(),
                'lastname' => $auteur->getLastname(),
                'email' => $auteur->getEmail()
            );
        }

        return new JsonResponse($results);
    }

    /**
     * Récupération d'un auteur spécifique suivant son identifiant
     *
     * @Route("/api/auteur/{id}")
     * @Method("GET")
     */
    public function getAuteurAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $auteur = $em->getRepository('GestionArticleBundle:Auteur')->findOneById($id);

        $result = array();

        if ($auteur) {
            $result = array(
                'id' => $auteur->getId(),
                'firstname' => $auteur->getFirstname(),
                'lastname' => $auteur->getLastname(),
                'email' => $auteur->getEmail()
            );
        }

        return new JsonResponse($result);
    }

    /**
     * Ajout d'un auteur
     *
     * @Route("/api/auteur")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');
        
        // Vérification des données
        $error = array();
        if (empty($firstname))
            $error[] = 'Votre prénom ne peut pas être vide !';
        if (empty($lastname))
            $error[] = 'Votre nom ne peut pas être vide !';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $error[] = "Veuillez entrer une adresse mail valide !";
        
        if (count($error) > 0) {
            return new JsonResponse($error);
        }
        
        $auteur = new Auteur();
        $auteur
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email);

        $em = $this->getDoctrine()->getManager();
        $em->persist($auteur);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * Modification d'un auteur
     *
     * @Route("/api/auteur")
     * @Method("PUT")
     */
    public function putAction(Request $request)
    {
        $id = $request->get('id');
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $email = $request->get('email');

        // Vérification des données
        $error = array();
        if (empty($firstname))
            $error[] = 'Votre prénom ne peut pas être vide !';
        if (empty($lastname))
            $error[] = 'Votre nom ne peut pas être vide !';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $error[] = "Veuillez entrer une adresse mail valide !";

        if (count($error) > 0) {
            return new JsonResponse($error);
        }

        $em = $this->getDoctrine()->getManager();
        $auteur = $em->getRepository('GestionArticleBundle:Auteur')->findOneById($id);
        $auteur
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email);

        $em->flush();

        return new JsonResponse(array(
            'id' => $auteur->getId(),
            'firstname' => $auteur->getFirstname(),
            'lastname' => $auteur->getLastname(),
            'email' => $auteur->getEmail()
        ));
    }

    /**
     * Suppression d'un auteur
     *
     * @Route("/api/auteur/{id}")
     * @Method("DELETE")
     */
    public function delAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $auteur = $em->getRepository('GestionArticleBundle:Auteur')->findOneById($id);

        $em->remove($auteur);
        $em->flush();

        return new JsonResponse();
    }
}
