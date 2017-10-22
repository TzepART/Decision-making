<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Criteria;
use AppBundle\Entity\Task;
use AppBundle\Entity\Variant;
use AppBundle\Form\TaskFormType;
use AppBundle\Form\Type\ExtendCriteriaType;
use AppBundle\Model\MatrixModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * @Route("/task", name="task")
 */
class TaskController extends Controller
{
    /**
     * @Route("/list/", name="task.list")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();

        return ['tasks' => $tasks];
    }

    /**
     * @Route("/new/", name="task.new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $task = new Task();

        $variant = new Variant();
        $variant->setName('');
        $task->getVariants()->add($variant);

        $criteria = new Criteria();
        $criteria->setName('');
        $criteria->setSignificance(0.00);
        $task->getCriteria()->add($criteria);

        $form = $this->createForm(TaskFormType::class, $task, [
            'action' => $this->generateUrl('task.create')
        ]);

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @Route("/view/{id}", name="task.view")
     * @Method("GET")
     * @Template()
     * @param Task $task
     * @return array
     */
    public function viewAction(Task $task)
    {
        $criteria_forms = [];

        /**
         * @var Criteria $criterion
         * */
        foreach ($task->getCriteria() as $index => $criterion) {
            $criteria_forms[] = $this->createForm(ExtendCriteriaType::class, $criterion, [
                'action' => $this->generateUrl('task.save_bo_matrix', ['id' => $criterion->getId()]),
                'method' => 'POST'
            ])->createView();
        }

        return [
            'task' => $task,
            'criteria_forms' => $criteria_forms,
        ];
    }


    /**
     * @Route("/edit/{id}", name="task.edit")
     * @Method("GET")
     */
    public function editAction(Task $task)
    {
        $form = $this->createUpdateForm($task);

        return $this->render('@App/Task/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/solution/{id}", name="task.solution")
     * @Method("GET")
     * @Template()
     * @param Task $task
     * @return array
     */
    public function solutionAction(Task $task)
    {
        $variants = [];
        $criterias = [];
        foreach ($task->getCriteria() as $i => $criterion) {
            /**
             * @var Criteria $criterion
             */
            $criterias[$criterion->getId()] = $criterion;
            foreach ($task->getVariants() as $j => $variant) {
                $variants[$variant->getId()] = $variant;
            }
        }
        //получим массив id вариантов отсортированных по коэфициентам
        $tournamentMechanismSolution = $this->get('app.task_manager')->getTournamentMechanismSolutionByTask($task);
        $solutionByDomination = $this->get('app.task_manager')->getSolutionByDominationMethod($task);
        $solutionByBlocked = $this->get('app.task_manager')->getSolutionByBlockedMethod($task);
//        dump($tournamentMechanismSolution);
//        dump($solutionByDomination);
//        dump($solutionByBlocked);
//        die();

        return [
            'tournamentMechanismSolution' => $tournamentMechanismSolution,
            'solutionByDomination' => $solutionByDomination,
            'solutionByBlocked' => $solutionByBlocked,
            'variants' => $variants,
            'criterias' => $criterias,
            'task' => $task
        ];
    }

    /**
     * @Route("/new", name="task.create")
     * @Method("POST")
     * @Template("AppBundle:Task:new.html.twig")
     * @ApiDoc(
     *  description="Create new task",
     *  resource=true,
     *  filters={
     *      {"name"="name", "dataType"="string", "description"="Name of task"}
     *  },
     *  section="Task"
     * )
     */
    public function createAction(Request $request)
    {
        $entity = new Task();
        $user = $this->getUser();

        $form = $this->createForm(TaskFormType::class, $entity, [
            'action' => $this->generateUrl('task.create'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($user);
            $em->persist($entity);
            $em->flush();

            $this->saveVariantsAndCriteria($entity, $form);

            return $this->redirectToRoute('task.view', ['id' => $entity->getId()]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/update/{id}", name="task.update")
     * @Method("POST")
     * @Template("AppBundle:Task:new.html.twig")
     * @ApiDoc(
     *  description="Update task",
     *  resource=true,
     *  filters={
     *      {"name"="name", "dataType"="string", "description"="Name of task"}
     *  },
     *  section="Task"
     * )
     * @param Task $task
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Task $task, Request $request)
    {
        $form = $this->createUpdateForm($task);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setUser($user);
            $em->persist($task);
            $em->flush();

            $this->saveVariantsAndCriteria($task, $form);

            return $this->redirectToRoute('task.view', ['id' => $task->getId()]);
        }

        return [
            'form' => $form->createView()
        ];
    }


    /**
     * @Route("/criteria/edit/{id}", name="task.save_bo_matrix")
     * @Method("POST")
     * @param Criteria $criteria
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addBinaryRelativeAction(Criteria $criteria, Request $request)
    {
        $matrix = new MatrixModel($request->request->get('criteria')['matrix']);
        $significance = $request->request->get('criteria')['significance'];

        $em = $this->getDoctrine()->getManager();
        $criteria->setMatrix($matrix);
        $criteria->setSignificance($significance);
        $em->persist($criteria);
        $em->flush();

        return $this->redirectToRoute('task.view', ['id' => $criteria->getTask()->getId()]);
    }

    /**
     * @param Task $task
     * @param Form $form
     */
    private function saveVariantsAndCriteria(Task $task, Form $form)
    {
        $em = $this->getDoctrine()->getManager();
        $variants = [];

        /**
         * @var Criteria $criteria
         * @var Variant $variant
         * */
        foreach ($form['variants']->getData() as $variant) {
            $variants[] = $variant->setTask($task);
            $em->persist($variant);
        }

        $emptyMatrix = new MatrixModel($this->get('app.matrix_manager')->getEmptyMatrixByVariants($variants));

        foreach ($form['criteria']->getData() as $criteria) {
            $criteria->setTask($task);
            $criteria->setMatrix($emptyMatrix);
            $em->persist($criteria);
        }

        $em->flush();

    }

    /**
     * @param Task $task
     * @return Form
     */
    protected function createUpdateForm(Task $task)
    {
        $form = $this->createForm(TaskFormType::class, $task, [
            'action' => $this->generateUrl('task.update',['id' => $task->getId()]),
            'method' => 'POST'
        ]);
        return $form;
    }

}
