<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LatestPhotoController extends AbstractController{

    /**
     * @Route ("/latest", name = "latest_photos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $latestPhotos = $em->getRepository(Photo::class)->findAllPublic();
         return $this->render('latest_photos/index.html.twig',[
             'latestPhotosPublic' => $latestPhotos]);
    }
}