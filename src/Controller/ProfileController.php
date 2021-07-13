<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    /**
     * @Route("/my/profile", name="my_profile")
     *
     */
    public function index()
    {
        if($this->getUser()) {
            return $this->render("my/profile.html.twig");
        }else{
            return $this->redirectToRoute("app_login");
        }
    }
}
