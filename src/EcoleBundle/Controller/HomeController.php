<?php

namespace EcoleBundle\Controller;

use EcoleBundle\Entity\Classe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Classe controller.
 *
 * @Route("/")
 */
class HomeController extends Controller
{
    /**
     * Lists all Classe entities.
     *
     * @Route("/", name="home")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $classe = $em->getRepository('EcoleBundle:Classe')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $prof = $em->getRepository('EcoleBundle:Prof')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $seance = $em->getRepository('EcoleBundle:Seance')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $eleve = $em->getRepository('EcoleBundle:Etudiant')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $note = $em->getRepository('EcoleBundle:Note')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $exercice = $em->getRepository('EcoleBundle:Exercice')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $absence = $em->getRepository('EcoleBundle:Absence')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();
        $evaluation = $em->getRepository('EcoleBundle:Evaluation')->createQueryBuilder('l')->select('COUNT(l)')->getQuery()->getSingleScalarResult();

        return $this->render('default/index.html.twig', [
            'classe' => $classe,
          'prof' => $prof,    'seance' => $seance,    'eleve' => $eleve,    'note' => $note,
           'exercice' => $exercice, 'absence' => $absence,     'e' => $evaluation,
        ]);
    }
}
