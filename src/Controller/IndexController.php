<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Recipe;
use App\Form\UploadPhotoType;
use App\Form\UploadRecipeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route ("/", name ="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadPhotoType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            if ( $this->getUser()){
                /**
                 * @var UploadedFile $pictureFileName
                 */
                $pictureFileName = $form->get('filename')->getData();
                $recipe = $form->get('recipe')->getData();
                if ($pictureFileName && $recipe){
                    try {
                        $orginalFileName = pathinfo($pictureFileName->getClientOriginalName(), PATHINFO_FILENAME );
                        $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',$orginalFileName);
                        $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFileName->guessExtension();
                        $pictureFileName->move('images/hosting', $newFileName);
                        $entityPhotos = new Photo();
                        $entityPhotos->setFilename($newFileName);
                        $entityPhotos->setIsPublic($form->get('is_public')->getData());
                        $entityPhotos->setUploadedAt(new \DateTime());
                        $entityPhotos->setUser($this->getUser());
                        $em->persist($entityPhotos);
                        $em->flush();

                        $entityRecipe = new Recipe();
                        $entityRecipe->setDescription($recipe);
                        $entityRecipe->setUploadedAt(new \DateTime());
                        $entityRecipe->setPhotoId($entityPhotos);
                        $em->persist($entityRecipe);
                        $em->flush();
                        // Double flush isn't a good practice

                        $this->addFlash('success','Recipe uploaded!');
                    }
                    catch(\Exception $e){
                        echo $e->getMessage();
                        echo '<br>'.$recipeId;
                        $this->addFlash('error','Something went wrong');
                    }
                }
            }
            else{
                $this->addFlash('error', 'You need to log in to add photo');
            }
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
