<?php

namespace EcoleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Seance controller.
 *
 * @Route("/emploi")
 */
class EmploiController extends Controller
{
    /**
     * Lists all Seance entities.
     *
     * @Route("/", name="emploi")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $seances = $em->getRepository('EcoleBundle:Seance')->findAll();

        return $this->render('emploi/index.html.twig', [
            'seances' => $seances,
        ]);
    }
}
