<?php

namespace EcoleBundle\Controller;

use EcoleBundle\Entity\Note;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Note controller.
 *
 * @Route("/note")
 */
class NoteController extends Controller
{
    /**
     * Lists all Note entities.
     *
     * @Route("/", name="note")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('EcoleBundle:Note')->createQueryBuilder('e');

        list($filterForm, $queryBuilder) = $this->filter($queryBuilder, $request);
        list($notes, $pagerHtml) = $this->paginator($queryBuilder, $request);

        $totalOfRecordsString = $this->getTotalOfRecordsString($queryBuilder, $request);

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
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
        $filterForm = $this->createForm('EcoleBundle\Form\NoteFilterType');

        // Reset filter
        if ('reset' == $request->get('filter_action')) {
            $session->remove('NoteControllerFilter');
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
                $session->set('NoteControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('NoteControllerFilter')) {
                $filterData = $session->get('NoteControllerFilter');

                foreach ($filterData as $key => $filter) { //fix for entityFilterType that is loaded from session
                    if (\is_object($filter)) {
                        $filterData[$key] = $queryBuilder->getEntityManager()->merge($filter);
                    }
                }

                $filterForm = $this->createForm('EcoleBundle\Form\NoteFilterType', $filterData);
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

            return $me->generateUrl('note', $requestParams);
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
     * Lists all Note entities.
     *
     * @Route("/new/notes/{id}", name="notes")
     * @Method({"POST","GET"})
     */
    public function notesNewAction(Request $request, $id)
    {  //les entree: evaluation id : done
        // les sorite
        $em = $this->getDoctrine()->getManager();

        /*
        $conn = $this->get('database_connection');*/
        $eva_object = $em->getRepository('EcoleBundle:Evaluation')->findOneBy(['id' => $id]);
        if (null == $eva_object) {
            $this->redirect($this->generateUrl('notes', []));
        }
        $students = $em->getRepository('EcoleBundle:Etudiant')->findBy(['classe' => $eva_object->getClasse()->getId()]);//get all student
        $evaluations = $em->getRepository('EcoleBundle:Evaluation')->findAll();

        if ($request->isMethod('POST')) {
            //need to be changed
            //get evaluation name
            foreach ($students as  $student) {
                $id_s = $student->getId();
                $classe = $student->getClasse();
                $n = $request->request->get('note' . $id_s);

                $note = new Note();
                $note->setValeur($n);
                $note->setClasse($classe);
                $note->setEtudiant($student);

                $note->setEvaluation($eva_object);
                $validator = $this->get('validator');
                $errors = $validator->validate($note);

                if (\count($errors) > 0) {
                    $this->get('session')->getFlashBag()->clear();
                    $request->getSession()
        ->getFlashBag()
        ->add('error', 'Les notes de cette evaluation sont déja rempli ou bien essaye de verifier les champs!')
    ;

                    return  $this->redirect($this->generateUrl('notes', ['id' => $id]));
                    // here just print that the evlaution is alreday added , just try to edit  it
                }
                $em->persist($note);
                $em->flush();

                /*
                $etudiant_object=new Etudiant();
                    $conn->insert('note', array('matiere' => 'jwage','valeur' => $n,'evaluation_id' => 1,'etudiant_id'=>$id));*/
    // $conn->update('note', array('matiere' => 'jwage','valeur' => $n,'evaluation_id' => 1,'etudiant_id'=>$id), array('id' => 1));
            }

            return $this->redirect($this->generateUrl('notes_update', ['id' => $id]));
        }

        unset($POST);

        //$

        if (isset($eva_object)) {
            return $this->render('note/notes.html.twig', ['students' => $students, 'evaluations' => $evaluations, 'id' => $id, 'eva_object' => $eva_object]);
        }

        return $this->render('note/notes.html.twig', ['students' => $students, 'evaluations' => $evaluations, 'id' => $id]);
    }

    /**
     * Lists all Note entities.
     *
     * @Route("/new/n", name="notes0")
     * @Method({"POST","GET"})
     */
    public function notes0Action(Request $request)
    {
        $this->get('session')->getFlashBag()->clear();
        $form = $this->createFormBuilder()->setAction($this->generateUrl('notes0'))

            ->add('classe', EntityType::class, [
    'class' => 'EcoleBundle:Classe',
    'choice_label' => function ($classe, $key, $index) {
        /** @var Category $category */
        return $classe->getDescription() . '_' . $classe->getAnnee();
    }, ])

            ->add('semestre', ChoiceType::class,
             [
    'choices' => ['1' => '1', '2' => '2'], ]
    )->getForm();
        $form->handleRequest($request);
        $i = $request->request->get('evluation');

        if ($form->isSubmitted() && null != $i) {
            /*if($i!=null)*/

            return $this->redirect($this->generateUrl('notes', ['id' => $i]));
        } elseif ($form->isSubmitted() && null == $i) {
            $request->getSession()
        ->getFlashBag()
        ->add('error', 'Choisissez svp une evaluation !!');

            return $this->render('note/notes0.html.twig', ['form' => $form->CreateView()]);
        }
        //prof ou bien evaluations
        return $this->render('note/notes0.html.twig', ['form' => $form->CreateView()]);
    }

    /**
     * Lists all Note entities.
     *
     * @Route("/new/nn", name="notes1")
     * @Method({"POST","GET"})
     */
    public function notes1Action(Request $request)
    {
        $this->get('session')->getFlashBag()->clear();
        $form = $this->createFormBuilder()->setAction($this->generateUrl('notes1'))

            ->add('classe', EntityType::class, [
    'class' => 'EcoleBundle:Classe',
    'choice_label' => function ($classe, $key, $index) {
        /** @var Category $category */
        return $classe->getDescription() . '_' . $classe->getAnnee();
    }, ])

            ->add('semestre', ChoiceType::class,
             [
    'choices' => ['1' => '1', '2' => '2'], ]
    )->getForm();
        $form->handleRequest($request);
        $i = $request->request->get('evluation');

        if ($form->isSubmitted() && null != $i) {
            /*if($i!=null)*/

            return $this->redirect($this->generateUrl('notes_update', ['id' => $i]));
        } elseif ($form->isSubmitted() && null == $i) {
            $request->getSession()
        ->getFlashBag()
        ->add('error', 'Choisissez svp une evaluation !!');

            return $this->render('note/notes1.html.twig', ['form' => $form->CreateView()]);
        }
        //prof ou bien evaluations
        return $this->render('note/notes1.html.twig', ['form' => $form->CreateView()]);
    }

    /**
     * Create filter form and process filter request.
     */

    /**
     * Lists all Note entities.
     *
     * @Route("/update/{id}", name="notes_update")
     * @Method({"POST","GET"})
     */
    public function notesUpdateAction(Request $request, $id)
    { //**//
        $em = $this->getDoctrine()->getManager();

        // les sorite

        /*
        $conn = $this->get('database_connection');*/
        $eva_object = $em->getRepository('EcoleBundle:Evaluation')->findOneBy(['id' => $id]);

        if (null == $eva_object) {
            $this->redirect($this->generateUrl('notes_update', ['id' => $id]));
        }

        $students = $em->getRepository('EcoleBundle:Etudiant')->findBy(['classe' => $eva_object->getClasse()->getId()]);//get all student

        $evaluations = $em->getRepository('EcoleBundle:Evaluation')->findAll();

        if ($request->isMethod('POST')) {
            //need to be changed
            //get evaluation name
            foreach ($students as  $student) {
                $id_s = $student->getId();
                $n = $request->request->get('note' . $id_s);
                $note = $em->getRepository('EcoleBundle:Note')->findOneBy(['evaluation' => $eva_object->getId(), 'etudiant' => $id_s]);

                if (null == $note) {
                    $classe = $student->getClasse();
                    $note = new Note();
                    $note->setValeur($n);
                    $note->setClasse($classe);
                    $note->setEtudiant($student);

                    $note->setEvaluation($eva_object);
                    $em->persist($note);
                    $em->flush();
                } else {
                    $note->setValeur($n);
                    $note->setEvaluation($eva_object);

                    $em->merge($note);
                    $em->flush();
                }

                /*
                $etudiant_object=new Etudiant();
                    $conn->insert('note', array('matiere' => 'jwage','valeur' => $n,'evaluation_id' => 1,'etudiant_id'=>$id));*/
    // $conn->update('note', array('matiere' => 'jwage','valeur' => $n,'evaluation_id' => 1,'etudiant_id'=>$id), array('id' => 1));
            }
        }

        unset($POST);

        //$

        if (isset($eva_object)) {
            return $this->render('note/update.html.twig', ['students' => $students, 'evaluations' => $evaluations, 'id' => $id, 'eva_object' => $eva_object]);
        }

        return $this->render('note/update.html.twig', ['students' => $students, 'evaluations' => $evaluations, 'id' => $id]);
    }

    /**
     * Lists all Note entities.
     *
     * @Route("/a", name="a")
     * @Method({"POST"})
     */
    public function ajaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /*
        $conn = $this->get('database_connection');*/

        $t = [];

        if ($request->isXMLHttpRequest()) {
            $choice = $request->request->get('choice');
            $classe = $request->request->get('classe');
            if (null != $choice) {
                $_SESSION['choice'] = $choice;
            }
            if (null != $classe) {
                $_SESSION['classe'] = $classe;
            }

            if (\array_key_exists('classe', $_SESSION) && \array_key_exists('choice', $_SESSION)) {
                $em = $this->getDoctrine()->getManager();
                $s = $em->getRepository('EcoleBundle:Evaluation')->findBy(['classe' => $_SESSION['classe'], 'semestre' => $_SESSION['choice']]);
                foreach ($s as $v) {
                    $t[$v->getId()] = $v->getDescription();
                    /* array_push($t,  array($v->getId => $v->getDescription()));*/
                }

                return new JsonResponse($t);
            } elseif (\array_key_exists('classe', $_SESSION) && !\array_key_exists('choice', $_SESSION)) {
                $em = $this->getDoctrine()->getManager();
                $s = $em->getRepository('EcoleBundle:Evaluation')->findBy(['classe' => $_SESSION['classe'], 'semestre' => 1]);
                foreach ($s as $v) {
                    $t[$v->getId()] = $v->getDescription();
                }

                return new JsonResponse($t);
            } elseif (!\array_key_exists('classe', $_SESSION) && \array_key_exists('choice', $_SESSION)) {
                $em = $this->getDoctrine()->getManager();
                $s = $em->getRepository('EcoleBundle:Evaluation')->findBy(['classe' => 1, 'semestre' => $_SESSION['choice']]);
                foreach ($s as $v) {
                    $t[$v->getId()] = $v->getDescription();
                }

                return new JsonResponse($t);
            }

            $em = $this->getDoctrine()->getManager();
            $s = $em->getRepository('EcoleBundle:Evaluation')->findBy(['classe' => 1, 'semestre' => 1]);
            foreach ($s as $v) {
                $t[$v->getId()] = $v->getDescription();
            }

            return new JsonResponse($t);
        }
    }

    /**
     * Finds and displays a Note entity.
     *
     * @Route("/{id}", name="note_show")
     * @Method("GET")
     */
    public function showAction(Note $note)
    {
        $deleteForm = $this->createDeleteForm($note);

        return $this->render('note/show.html.twig', [
            'note' => $note,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Note entity.
     *
     * @Route("/{id}/edit", name="note_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Note $note)
    {
        $deleteForm = $this->createDeleteForm($note);
        $editForm = $this->createForm('EcoleBundle\Form\NoteType', $note);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Edited Successfully!');

            return $this->redirectToRoute('note_edit', ['id' => $note->getId()]);
        }

        return $this->render('note/edit.html.twig', [
            'note' => $note,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Note entity.
     *
     * @Route("/{id}", name="note_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Note $note)
    {
        $form = $this->createDeleteForm($note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($note);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Note was deleted successfully');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Note');
        }

        return $this->redirectToRoute('note');
    }

    /**
     * Creates a form to delete a Note entity.
     *
     * @param Note $note The Note entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Note $note)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('note_delete', ['id' => $note->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Delete Note by id.
     *
     * @Route("/delete/{id}", name="note_by_id_delete")
     * @Method("GET")
     */
    public function deleteByIdAction(Note $note)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $em->remove($note);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'The Note was deleted successfully');
        } catch (Exception $ex) {
            $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the Note');
        }

        return $this->redirect($this->generateUrl('note'));
    }

    /**
     * Bulk Action.
     * @Route("/bulk-action/", name="note_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->get('ids', []);
        $action = $request->get('bulk_action', 'delete');

        if ('delete' == $action) {
            try {
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('EcoleBundle:Note');

                foreach ($ids as $id) {
                    $note = $repository->find($id);
                    $em->remove($note);
                    $em->flush();
                }

                $this->get('session')->getFlashBag()->add('success', 'notes was deleted successfully!');
            } catch (Exception $ex) {
                $this->get('session')->getFlashBag()->add('error', 'Problem with deletion of the notes ');
            }
        }

        return $this->redirect($this->generateUrl('note'));
    }
}
