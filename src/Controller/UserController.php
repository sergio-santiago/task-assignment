<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController'
        ]);
    }
}
