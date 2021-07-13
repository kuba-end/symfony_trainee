<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormTypeC;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    public $user;
    /**
     * @Route("/my/profile/password/change/", name="my_profile_password_change")
     * @param int $id
     */


    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        if ($this->getUser()) {

            $em = $this->getDoctrine()->getManager();
            $allUsers = $em->getRepository(User::class)->findAll();


            $form = $this->createForm(ChangePasswordFormTypeC::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {

                    foreach ( $allUsers as $entity)
                    {
                        if( $this->getUser()->getUsername() == $entity->getUsername())
                        {
                            $this->user = $entity;
                            if($this->getUser()->getPassword() == $passwordHasher->isPasswordValid($this->user,$form->get('password')->getData()))
                            {

                                $this->user->setPassword($passwordHasher->hashPassword($this->user, $form->get('newPassword')->getData()));
                                $em->flush();
                            }else{
                                $this->addFlash('error','Old password is not correct');
                            }
                        }
                    }

            }
            return $this->render('security/password_change.html.twig', [
                'changePasswordForm' => $form->createView()
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}