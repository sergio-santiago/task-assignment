<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = 'SELECT t FROM App\Entity\Task t ORDER BY t.id DESC';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 15
        );

        return $this->render('task/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $task->setStatus(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();

                $translatedMessage = $this->get('translator')->trans('The task was created correctly');
                $this->addFlash('success', $translatedMessage);

                return $this->redirectToRoute('task_index');
            } catch (\Exception $exception) {
                if ($_ENV['APP_ENV'] == "dev") {
                    throw $exception;
                } else {
                    $translatedError = $this->get('translator')->trans('An error occurred while creating the task');
                    $this->addFlash('danger', $translatedError);
                }
            }
        }

        return $this->render('task/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
