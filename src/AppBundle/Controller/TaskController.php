<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $form = $this->createForm(TaskFormType::class, null, [
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

            return $this->redirectToRoute('homepage');
        }

        return [
            'form' => $form->createView()
        ];
    }
}
