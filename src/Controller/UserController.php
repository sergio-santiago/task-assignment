<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = 'SELECT u FROM App\Entity\User u';
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 10
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $pass = $form->get('password')->getData();
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

        return $this->render('user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function view($id)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);
        //$user = $repository->findOneBy(['id' => $id]);

        return $this->render('user/view.html.twig', [
            'user' => $user
        ]);
    }
}
