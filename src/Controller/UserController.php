<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $searchQuery = $request->get('search-query');
        if (!empty($searchQuery)) {
            $finder = $this->container->get('fos_elastica.finder.users.user');
            $query = $finder->createPaginatorAdapter($searchQuery);
        } else {
            $em = $this->get('doctrine.orm.entity_manager');
            $dql = 'SELECT u FROM App\Entity\User u ORDER BY u.id DESC';
            $query = $em->createQuery($dql);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 15
        );

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', ['id' => ':USER_ID']))
            ->setMethod('DELETE')
            ->getForm();

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
            'delete_form' => $deleteForm->createView(),
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
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $pass = $form->get('password')->getData();
            $passConstarint = new Assert\NotBlank(['message' => 'The password can not be empty']);
            $errorList = $this->get('validator')->validate($pass, $passConstarint);
            $passValid = (count($errorList) == 0) ? true : false;

            if (!$passValid) {
                foreach ($errorList as $error) {
                    $formError = new FormError($error->getMessage());
                    $form->get('password')->addError($formError);
                }
            }

            if ($form->isValid() && $passValid) {
                try {
                    $encodedPass = $this->container->get('security.password_encoder')->encodePassword($user, $pass);
                    $user->setPassword($encodedPass);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    $translatedMessage = $this->get('translator')->trans('The user was created correctly');
                    $this->addFlash('success', $translatedMessage);

                    return $this->redirectToRoute('user_index');
                } catch (\Exception $exception) {
                    if ($_ENV['APP_ENV'] == "dev") {
                        throw $exception;
                    } else {
                        $translatedError = $this->get('translator')->trans('An error occurred while creating the user');
                        $this->addFlash('danger', $translatedError);
                    }
                }
            }
        }

        return $this->render('user/add.html.twig', [
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
        //$em = $this->get('doctrine.orm.entity_manager');
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            $messageException = $this->get('translator')->trans('User not found');
            throw $this->createNotFoundException($messageException);
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $pass = $form->get('password')->getData();
                if (!empty($pass)) {
                    $encodedPass = $this->container->get('security.password_encoder')->encodePassword($user, $pass);
                    $user->setPassword($encodedPass);
                } else {
                    $query = $em->createQuery(
                        'SELECT u.password FROM App\Entity\User u WHERE u.id = :id'
                    )->setParameter('id', $id);
                    $currentPass = $query->execute();

                    $user->setPassword($currentPass[0]['password']);
                }

                $em->flush();

                $translatedMessage = $this->get('translator')->trans('The user was updated correctly');
                $this->addFlash('success', $translatedMessage);

                return $this->redirectToRoute('user_index');
            } catch (\Exception $exception) {
                if ($_ENV['APP_ENV'] == "dev") {
                    throw $exception;
                } else {
                    $translatedError = $this->get('translator')->trans('An error occurred while updating the user');
                    $this->addFlash('danger', $translatedError);
                }
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        if (!$user) {
            $messageException = $this->get('translator')->trans('User not found');
            throw $this->createNotFoundException($messageException);
        }

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        return $this->render('user/view.html.twig', [
            'user' => $user,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request, $id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository(User::class);
            $user = $repository->find($id);

            if (!$user) {
                $messageException = $this->get('translator')->trans('User not found');
                throw $this->createNotFoundException($messageException);
            }

            $deleteForm = $this->createFormBuilder()
                ->setAction($this->generateUrl('user_delete', ['id' => $user->getId()]))
                ->setMethod('DELETE')
                ->getForm();

            $deleteForm->handleRequest($request);

            if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
                $deleteResult = ['removed' => null, 'message' => null];

                if ($user->getRole() == 'ROLE_USER') {
                    $em->remove($user);
                    $em->flush();

                    $deleteResult['removed'] = true;
                    $deleteResult['message'] = $this->get('translator')->trans('The user was successfully deleted');
                } else {
                    $deleteResult['removed'] = false;
                    $deleteResult['message'] = $this->get('translator')->trans('Users with role admin can not be deleted');
                }

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse($deleteResult);
                } else {
                    $flashType = ($deleteResult['removed']) ? 'success' : 'danger';
                    $this->addFlash($flashType, $deleteResult['message']);
                }
            }
        } catch (\Exception $exception) {
            if ($_ENV['APP_ENV'] == "dev") {
                throw $exception;
            } else {
                $translatedError = $this->get('translator')->trans('An error occurred while deleting the user');
                $this->addFlash('danger', $translatedError);
            }
        }

        return $this->redirectToRoute('user_index');
    }
}
