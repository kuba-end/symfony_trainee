<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Service\PhotoVisibilityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class MyController
 * @IsGranted("ROLE_USER")
 */
class MyController extends AbstractController{

    /**
     * @Route("/my/photos", name="my_photos")
     */

    public function index(){

        $em = $this->getDoctrine()->getManager();
        $myPhotos = $em->getRepository(Photo::class)->findBy([
            'user' => $this->getUser()
        ]);
        return $this->render('my/photos.html.twig',[
            'myPhotos' => $myPhotos
        ]);
    }

    /**
     * @Route("my/photos/set_visibility/{id}/{visibility}", name="my_photo_set_visibility")
     * @param PhotoVisibilityService $photoVisibilityService
     * @param int $id
     * @param bool $visibility
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function myPhotoChangeVisibility(PhotoVisibilityService $photoVisibilityService, int $id, bool $visibility)
    {
        $message = [
            1 => 'public',
            0 => 'private'
        ];
        if($photoVisibilityService->makeVisible($id,$visibility))
        {
            $this->addFlash('success','Photo changed to '.$message[$visibility].'.');
        }else{
            $this->addFlash('error','Photo changed to '.$message[$visibility].'.');

        }
        return $this->redirectToRoute("my_photos");
    }

    /**
     * @Route ("my/photos/remove/{id}", name="my_photos_remove")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function myPhotoRemove(int $id){

        $em = $this->getDoctrine()->getManager();
        $myPhoto = $em ->getRepository(Photo::class)->find($id);


            if ($this->getUser() == $myPhoto->getUser())
            {
                $fileManager = new Filesystem();
                $fileManager->remove("images/hosting" . $myPhoto->getFilename());
                if ($fileManager->exists("images/hosting" . $myPhoto->getFilename()))
                {
                    $this->addFlash('erorr', 'Something went wrong, file has not been deleted');
                } else{
                    $em->remove($myPhoto);
                    $em->flush();
                    $this->addFlash('success', 'File has been removed');
                    }
            }
        else {
            $this->addFlash('error', "You can't remove this photo. Log in as a owner.");
        }
            return $this->redirectToRoute('my_photos');

    }
}