<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Criteria;
use AppBundle\Entity\Task;
use AppBundle\Entity\Variant;
use AppBundle\Form\TaskFormType;
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
     * @Route("/list/{id}", name="task.view")
     * @Method("GET")
     * @Template()
     * @param Task $task
     * @return array
     */
    public function viewAction(Task $task)
    {
        return ['task' => $task];
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
        $task->getCriteria()->add($criteria);

        $form = $this->createForm(TaskFormType::class, $task, [
            'action' => $this->generateUrl('task.create'),
        ]);

        return [
            'form' => $form->createView()
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
     * @param Task $task
     * @param Form $form
     */
    private function saveVariantsAndCriteria(Task $task, Form $form)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var Criteria $criteria
         * @var Variant $variant
         * */
        foreach ($form['criteria']->getData() as $criteria) {
            $criteria->setTask($task);
            $em->persist($criteria);
        }

        foreach ($form['variants']->getData() as $variant) {
            $variant->setTask($task);
            $em->persist($variant);
        }

        $em->flush();

    }
}
