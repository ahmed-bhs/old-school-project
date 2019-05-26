<?php

namespace EcoleBundle\Controller;

use EcoleBundle\Entity\Classe;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Classe controller.
 *
 * @Route("/classe")
 */
class ClasseController extends Controller
{
    /**
     * Lists all Classe entities.
     *
     * @Route("/", name="classe")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('EcoleBundle:Classe')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($classes, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
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
        $filterForm = $this->createForm('EcoleBundle\Form\ClasseFilterType');

        // Reset filter
        if ('reset' == $request->get('filter_action')) {
            $session->remove('ClasseControllerFilter');
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
                $session->set('ClasseControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('ClasseControllerFilter')) {
                $filterData = $session->get('ClasseControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (\is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('EcoleBundle\Form\ClasseFilterType', $filterData);
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

            return $me->generateUrl('classe', $requestParams);
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
     * Displays a form to create a new Classe entity.
     *
     * @Route("/new", name="classe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $classe = new Classe();
        $form = $this->createForm('EcoleBundle\Form\ClasseType', $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();

            $editLink = $this->generateUrl('classe_edit', ['id' => $classe->getId()]);
            $this->get('session')->getFlashBag()->add('success', "<a href='$editLink'>
La nouvelle classe a été crée avec succès.</a>");

            $nextAction = 'save' == $request->get('submit') ? 'classe' : 'classe_new';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Classe entity.
     *
     * @Route("/{id}", name="classe_show")
     * @Method("GET")
     */
    public function showAction(Classe $classe)
    {
        $deleteForm = $this->createDeleteForm($classe);

        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Classe entity.
     *
     * @Route("/{id}/edit", name="classe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Classe $classe)
    {
        $deleteForm = $this->createDeleteForm($classe);
        $editForm = $this->createForm('EcoleBundle\Form\ClasseType', $classe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', '
Édition réussie!');

            return $this->redirectToRoute('classe_edit', ['id' => $classe->getId()]);
        }

        return $this->render('classe/edit.html.twig', [
            'classe' => $classe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Classe entity.
     *
     * @Route("/{id}", name="classe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Classe $classe)
    {
        $form = $this->createDeleteForm($classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($classe);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La classe a été supprimée avec succès');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problème de suppression de la Classe');
        }

        return $this->redirectToRoute('classe');
    }

    /**
     * Creates a form to delete a Classe entity.
     *
     * @param Classe $classe The Classe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Classe $classe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('classe_delete', ['id' => $classe->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Classe by id.
     *
     * @Route("/delete/{id}", name="classe_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Classe $classe)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($classe);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'La classe a été supprimée avec succès
');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problème de suppression de la Classe');
        }

        return $this->redirect($this->generateUrl('classe'));
    }

    /**
     * Bulk Action.
     * @Route("/bulk-action/", name="classe_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get('ids', []);
        $action = $request->get('bulk_action', 'delete');

        if ('delete' == $action) {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('EcoleBundle:Classe');

                foreach ($ids as $id) {
                    $classe = $repository->find($id);
                    $em->remove($classe);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'La classe a été supprimée avec succès
!');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the classes ');
            }
        }

        return $this->redirect($this->generateUrl('classe'));
    }
}
