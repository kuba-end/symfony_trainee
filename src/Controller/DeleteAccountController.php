<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\DeleteAccountFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DeleteAccountController extends AbstractController
{

    public object $controller;

    public function __construct(SecurityController $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @Route("/my/profile/delete/account", name="delete_account")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request,
                          TokenStorageInterface $tokenStorage,
                          SessionInterface $session,
                          UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->getUser()) {


            $em = $this->getDoctrine()->getManager();
            $allUsers = $em->getRepository(User::class)->findAll();

            $myPhotos = $em->getRepository(Photo::class)->findBy([
                'user' => $this->getUser()
            ]);
            $form = $this->createForm(DeleteAccountFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                foreach ($allUsers as $entity)
                {
                    if ($this->getUser()->getUsername() == $entity->getUsername())
                    {
                        if ($this->getUser()->getPassword() == $passwordHasher->isPasswordValid($entity, $form->get('Password')->getData())) {
                            foreach ($myPhotos as $photo) {

                                $em->remove($photo);
                                $em->flush();
                            }
                                $em->remove($this->getUser());
                                $em->flush();

                                $tokenStorage->setToken(null);
                                $session->invalidate();


                                return $this->redirectToRoute("index");
                        }else{
                            $this->addFlash('accountDeleteError','Wrong password, you need to authenticate to proceed');
                        }

                    }
                }
            }
        }

        return $this->render('delete_account/index.html.twig', [
            'deleteAccountForm' => $form->createView()
        ]);
    }
}