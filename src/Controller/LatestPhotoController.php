<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LatestPhotoController extends AbstractController{

    #[Route("/latest", name: "latest_photos")]
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $latestPhotos = $em->getRepository(Photo::class)->findBy(['is_public'=>true]);
         return $this->render('latest_photos/index.html.twig',[
             'latestPhotosPublic' => $latestPhotos]);
    }
}