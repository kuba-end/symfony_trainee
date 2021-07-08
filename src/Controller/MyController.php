<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyController extends AbstractController{

    #[Route("/my/photos", name:"my_photos")]
    public function index(){

    }

    #[Route("my/photos/set_private/{id}", name:"my_photo_set_as_private")]
    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function myPhotoSetAsPrivate(int $id){

        $em= $this->getDoctrine()->getManager();
        $myPhoto = $em->getRepository(Photo::class)->find($id);
        if ($this->getUser() == $myPhoto->getUser()){
            try {
                $myPhoto->setIsPublic(0);
                $em->persist($myPhoto);
                $em->flush();
                $this->addFlash('success','This photo is private now');
            } catch (\Exception $e){
                $this->addFlash('error','Something went wrong');
            }
            $this->addFlash('error', "You can't change settings of this photo. Log in as a owner.");
        }
        return $this->redirectToRoute('latest_photos');
    }
}