<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $searchQuery = $request->get('search-query');
        if (!empty($searchQuery)) {
            $finder = $this->container->get('fos_elastica.finder.tasks.task');
            $query = $finder->createPaginatorAdapter($searchQuery);
        } else {
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = 'SELECT t FROM App\Entity\Task t ORDER BY t.id DESC';
            $query = $em->createQuery($dql);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 15
        );

        return $this->render('task/index.html.twig', [
            'pagination' => $pagination,
            'search_query' => $searchQuery
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

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit($id, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            $messageException = $this->get('translator')->trans('Task not found');
            throw $this->createNotFoundException($messageException);
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $task->setStatus(false);
                $em->flush();

                $translatedMessage = $this->get('translator')->trans('The task was updated correctly');
                $this->addFlash('success', $translatedMessage);

                return $this->redirectToRoute('task_index');
            } catch (\Exception $exception) {
                if ($_ENV['APP_ENV'] == "dev") {
                    throw $exception;
                } else {
                    $translatedError = $this->get('translator')->trans('An error occurred while updating the task');
                    $this->addFlash('danger', $translatedError);
                }
            }
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view($id)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($id);

        if (!$task) {
            $messageException = $this->get('translator')->trans('Task not found');
            throw $this->createNotFoundException($messageException);
        }

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', ['id' => $task->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        return $this->render('task/view.html.twig', [
            'task' => $task,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request, $id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(Task::class);
            $task = $repository->find($id);

            if (!$task) {
                $messageException = $this->get('translator')->trans('Task not found');
                throw $this->createNotFoundException($messageException);
            }

            $deleteForm = $this->createFormBuilder()
                ->setAction($this->generateUrl('task_delete', ['id' => $task->getId()]))
                ->setMethod('DELETE')
                ->getForm();

            $deleteForm->handleRequest($request);

            if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
                $em->remove($task);
                $em->flush();
                $this->addFlash('success', $this->get('translator')->trans('The task was successfully deleted'));
            }
        } catch (\Exception $exception) {
            if ($_ENV['APP_ENV'] == "dev") {
                throw $exception;
            } else {
                $translatedError = $this->get('translator')->trans('An error occurred while deleting the task');
                $this->addFlash('danger', $translatedError);
            }
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myTasks(Request $request)
    {
        $userId = $this->getUser()->getId();
        $searchQuery = $request->get('search-query');
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Task::class);

        if (!empty($searchQuery)) {
            $query = $repository->createQueryBuilder('t')
                ->join('t.user', 'u')
                ->where('u.id = :userId')
                ->andWhere('t.title LIKE :taskTitle')
                ->orderBy('t.id', 'DESC')
                ->setParameters([
                    'userId' => $userId,
                    'taskTitle' => '%' . $searchQuery . '%'
                ])
                ->getQuery();
        } else {
            $query = $repository->createQueryBuilder('t')
                ->join('t.user', 'u')
                ->where('u.id = :userId')
                ->orderBy('t.id', 'DESC')
                ->setParameter('userId', $userId)
                ->getQuery();
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 15
        );

        $completeForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('task_complete', ['id' => ':TASK_ID']))
            ->setMethod('POST')
            ->getForm();

        return $this->render('task/my_tasks.html.twig', [
            'pagination' => $pagination,
            'search_query' => $searchQuery,
            'complete_form' => $completeForm->createView()
        ]);
    }

    public function complete(Request $request, $id)
    {
        $completeResult = ['completed' => false, 'message' => null];

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Task::class);
        $task = $repository->find($id);

        if (!$task) {
            $messageException = $this->get('translator')->trans('Task not found');
            throw $this->createNotFoundException($messageException);
        }

        $completeForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('task_complete', ['id' => $task->getId()]))
            ->setMethod('POST')
            ->getForm();

        $completeForm->handleRequest($request);

        if ($completeForm->isSubmitted() && $completeForm->isValid()) {
            if ($request->isXmlHttpRequest()) {
                try {
                    $task->setStatus(true);
                    $em->persist($task);
                    $em->flush();

                    $completeResult['completed'] = true;
                    $completeResult['message'] = $this->get('translator')->trans('The task has been completed');
                } catch (\Exception $exception) {
                    $completeResult['message'] = $exception->getMessage();
                }
            }
        }

        return new JsonResponse($completeResult);
    }
}
