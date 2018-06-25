<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

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

                $this->addFlash('success', 'The user ' . $user->getUsername() . ' has been created');
                return $this->redirectToRoute('user_index');
            } catch (\Exception $exception) {
                $this->addFlash('danger', 'An error occurred while creating the user');
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
