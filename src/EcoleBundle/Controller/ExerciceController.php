<?php

namespace EcoleBundle\Controller;

use EcoleBundle\Entity\Exercice;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Exercice controller.
 *
 * @Route("/exercice")
 */
class ExerciceController extends Controller
{
    /**
     * Lists all Exercice entities.
     *
     * @Route("/", name="exercice")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('EcoleBundle:Exercice')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($exercices, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('exercice/index.html.twig', [
            'exercices' => $exercices,
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
        $filterForm = $this->createForm('EcoleBundle\Form\ExerciceFilterType');

        // Reset filter
        if ('reset' == $request->get('filter_action')) {
            $session->remove('ExerciceControllerFilter');
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
                $session->set('ExerciceControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ExerciceControllerFilter')) {
                $filterData = $session->get('ExerciceControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (\is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('EcoleBundle\Form\ExerciceFilterType', $filterData);
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

            return $me->generateUrl('exercice', $requestParams);
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
     * Displays a form to create a new Exercice entity.
     *
     * @Route("/new", name="exercice_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $exercice = new Exercice();
        $form = $this->createForm('EcoleBundle\Form\ExerciceType', $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exercice);
            $em->flush();

            $editLink = $this->generateUrl('exercice_edit', ['id' => $exercice->getId()]);
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>La nouvelle  exercice  a été crée avec succès.</a>");

            $nextAction = 'save' == $request->get('submit') ? 'exercice' : 'exercice_new';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('exercice/new.html.twig', [
            'exercice' => $exercice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Exercice entity.
     *
     * @Route("/{id}", name="exercice_show")
     * @Method("GET")
     */
    public function showAction(Exercice $exercice)
    {
        $deleteForm = $this->createDeleteForm($exercice);

        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Exercice entity.
     *
     * @Route("/{id}/edit", name="exercice_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Exercice $exercice)
    {
        $deleteForm = $this->createDeleteForm($exercice);
        $editForm = $this->createForm('EcoleBundle\Form\ExerciceType', $exercice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exercice);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');

            return $this->redirectToRoute('exercice_edit', ['id' => $exercice->getId()]);
        }

        return $this->render('exercice/edit.html.twig', [
            'exercice' => $exercice,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Exercice entity.
     *
     * @Route("/{id}", name="exercice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Exercice $exercice)
    {
        $form = $this->createDeleteForm($exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($exercice);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Exercice was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Exercice');
        }

        return $this->redirectToRoute('exercice');
    }

    /**
     * Creates a form to delete a Exercice entity.
     *
     * @param Exercice $exercice The Exercice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Exercice $exercice)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('exercice_delete', ['id' => $exercice->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Exercice by id.
     *
     * @Route("/delete/{id}", name="exercice_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Exercice $exercice)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($exercice);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Exercice was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Exercice');
        }

        return $this->redirect($this->generateUrl('exercice'));
    }

    /**
     * Bulk Action.
     * @Route("/bulk-action/", name="exercice_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get('ids', []);
        $action = $request->get('bulk_action', 'delete');

        if ('delete' == $action) {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('EcoleBundle:Exercice');

                foreach ($ids as $id) {
                    $exercice = $repository->find($id);
                    $em->remove($exercice);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'exercices was deleted successfully!');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the exercices ');
            }
        }

        return $this->redirect($this->generateUrl('exercice'));
    }
}
