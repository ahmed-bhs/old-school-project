<?php

namespace EcoleBundle\Controller;

use EcoleBundle\Entity\Absence;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Absence controller.
 *
 * @Route("/absence")
 */
class AbsenceController extends Controller
{
    /**
     * Lists all Absence entities.
     *
     * @Route("/", name="absence")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('EcoleBundle:Absence')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($absences, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('absence/index.html.twig', [
            'absences' => $absences,
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
        $filterForm = $this->createForm('EcoleBundle\Form\AbsenceFilterType');

        // Reset filter
        if ('reset' == $request->get('filter_action')) {
            $session->remove('AbsenceControllerFilter');
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
                $session->set('AbsenceControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AbsenceControllerFilter')) {
                $filterData = $session->get('AbsenceControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (\is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('EcoleBundle\Form\AbsenceFilterType', $filterData);
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

            return $me->generateUrl('absence', $requestParams);
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
     * Displays a form to create a new Absence entity.
     *
     * @Route("/new", name="absence_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $absence = new Absence();
        $form = $this->createForm('EcoleBundle\Form\AbsenceType', $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($absence);
            $em->flush();

            $editLink = $this->generateUrl('absence_edit', ['id' => $absence->getId()]);
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>La nouvelle  absence  a été crée avec succès.</a>");

            $nextAction = 'save' == $request->get('submit') ? 'absence' : 'absence_new';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('absence/new.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Absence entity.
     *
     * @Route("/{id}", name="absence_show")
     * @Method("GET")
     */
    public function showAction(Absence $absence)
    {
        $deleteForm = $this->createDeleteForm($absence);

        return $this->render('absence/show.html.twig', [
            'absence' => $absence,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Absence entity.
     *
     * @Route("/{id}/edit", name="absence_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Absence $absence)
    {
        $deleteForm = $this->createDeleteForm($absence);
        $editForm = $this->createForm('EcoleBundle\Form\AbsenceType', $absence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($absence);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');

            return $this->redirectToRoute('absence_edit', ['id' => $absence->getId()]);
        }

        return $this->render('absence/edit.html.twig', [
            'absence' => $absence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Absence entity.
     *
     * @Route("/{id}", name="absence_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Absence $absence)
    {
        $form = $this->createDeleteForm($absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($absence);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Absence was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Absence');
        }

        return $this->redirectToRoute('absence');
    }

    /**
     * Creates a form to delete a Absence entity.
     *
     * @param Absence $absence The Absence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Absence $absence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('absence_delete', ['id' => $absence->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Absence by id.
     *
     * @Route("/delete/{id}", name="absence_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Absence $absence)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($absence);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Absence was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Absence');
        }

        return $this->redirect($this->generateUrl('absence'));
    }

    /**
     * Bulk Action.
     * @Route("/bulk-action/", name="absence_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get('ids', []);
        $action = $request->get('bulk_action', 'delete');

        if ('delete' == $action) {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('EcoleBundle:Absence');

                foreach ($ids as $id) {
                    $absence = $repository->find($id);
                    $em->remove($absence);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'absences was deleted successfully!');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the absences ');
            }
        }

        return $this->redirect($this->generateUrl('absence'));
    }
}
