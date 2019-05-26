<?php

namespace EcoleBundle\Controller;

use EcoleBundle\Entity\Evaluation;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Evaluation controller.
 *
 * @Route("/evaluation")
 */
class EvaluationController extends Controller
{
    /**
     * Lists all Evaluation entities.
     *
     * @Route("/", name="evaluation")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('EcoleBundle:Evaluation')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($evaluations, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluations,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
            'totalOfRecordsString' => $totalOfRecordsString,
        ]);
    }

    /**
     * Create filter form and process filter request.
     */
    protected function filter($queryBuilder, Request $request)
    {
        $session = $request->getSession();
        $filterForm = $this->createForm('EcoleBundle\Form\EvaluationFilterType');

        // Reset filter
        if ('reset' == $request->get('filter_action')) {
            $session->remove('EvaluationControllerFilter');
        }

        // Filter action
        if ('filter' == $request->get('filter_action')) {
            // Bind values from the request
            $filterForm->handleRequest($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('EvaluationControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('EvaluationControllerFilter')) {
                $filterData = $session->get('EvaluationControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (\is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('EcoleBundle\Form\EvaluationFilterType', $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return [$filterForm, $queryBuilder];
    }

    /**
     * Get results from paginator and get paginator view.
     */
    protected function paginator($queryBuilder, Request $request)
    {
        //sorting
        $sortCol = $queryBuilder->getRootAlias() . '.' . $request->get('pcg_sort_col', 'id');
        $queryBuilder->orderBy($sortCol, $request->get('pcg_sort_order', 'desc'));
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($request->get('pcg_show', 10));

        try {
            $pagerfanta->setCurrentPage($request->get('pcg_page', 1));
        } catch (\Pagerfanta\Exception\OutOfRangeCurrentPageException $ex) {
            $pagerfanta->setCurrentPage(1);
        }

        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function ($page) use ($me, $request) {
            $requestParams = $request->query->all();
            $requestParams['pcg_page'] = $page;

            return $me->generateUrl('evaluation', $requestParams);
        };

        // Paginator - view
        $view = new TwitterBootstrap3View();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, [
            'proximity' => 3,
            'prev_message' => 'previous',
            'next_message' => 'next',
        ]);

        return [$entities, $pagerHtml];
    }

    /*
     * Calculates the total of records string
     */
    protected function getTotalOfRecordsString($queryBuilder, $request)
    {
        $totalOfRecords = $queryBuilder->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $show = $request->get('pcg_show', 10);
        $page = $request->get('pcg_page', 1);

        $startRecord = ($show * ($page - 1)) + 1;
        $endRecord = $show * $page;

        if ($endRecord > $totalOfRecords) {
            $endRecord = $totalOfRecords;
        }

        return "Showing $startRecord - $endRecord of $totalOfRecords Records.";
    }

    /**
     * Displays a form to create a new Evaluation entity.
     *
     * @Route("/new", name="evaluation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $evaluation = new Evaluation();
        $form = $this->createForm('EcoleBundle\Form\EvaluationType', $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evaluation);
            $em->flush();

            $editLink = $this->generateUrl('evaluation_edit', ['id' => $evaluation->getId()]);
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>La nouvelle  evaluation  a été crée avec succès.</a>");

            $nextAction = 'save' == $request->get('submit') ? 'evaluation' : 'evaluation_new';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Evaluation entity.
     *
     * @Route("/{id}", name="evaluation_show")
     * @Method("GET")
     */
    public function showAction(Evaluation $evaluation)
    {
        $deleteForm = $this->createDeleteForm($evaluation);

        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Evaluation entity.
     *
     * @Route("/{id}/edit", name="evaluation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Evaluation $evaluation)
    {
        $deleteForm = $this->createDeleteForm($evaluation);
        $editForm = $this->createForm('EcoleBundle\Form\EvaluationType', $evaluation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evaluation);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');

            return $this->redirectToRoute('evaluation_edit', ['id' => $evaluation->getId()]);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Evaluation entity.
     *
     * @Route("/{id}", name="evaluation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Evaluation $evaluation)
    {
        $form = $this->createDeleteForm($evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evaluation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Evaluation was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Evaluation');
        }

        return $this->redirectToRoute('evaluation');
    }

    /**
     * Creates a form to delete a Evaluation entity.
     *
     * @param Evaluation $evaluation The Evaluation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evaluation $evaluation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evaluation_delete', ['id' => $evaluation->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Evaluation by id.
     *
     * @Route("/delete/{id}", name="evaluation_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Evaluation $evaluation)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($evaluation);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Evaluation was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Evaluation');
        }

        return $this->redirect($this->generateUrl('evaluation'));
    }

    /**
     * Bulk Action.
     * @Route("/bulk-action/", name="evaluation_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get('ids', []);
        $action = $request->get('bulk_action', 'delete');

        if ('delete' == $action) {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('EcoleBundle:Evaluation');

                foreach ($ids as $id) {
                    $evaluation = $repository->find($id);
                    $em->remove($evaluation);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'evaluations was deleted successfully!');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the evaluations ');
            }
        }

        return $this->redirect($this->generateUrl('evaluation'));
    }
}
