<?php

namespace App\Service;

use App\Controller\SecurityController;
use App\Entity\Photo;
use App\Entity\User;
use App\Form\DeleteAccountFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccountSettingsService extends AbstractController
{
    public object $controller;
    public object $request;
    public mixed $tokenStorage;
    public object $session;
    public mixed $passwordHasher;
    public mixed $entityManager;

    public function __construct(SecurityController $controller,
                                RequestStack $requestStack,
                                TokenStorageInterface $tokenStorage,
                                SessionInterface $session,
                                UserPasswordHasherInterface $passwordHasher,
                                EntityManagerInterface $entityManager)
    {
        $this->controller=$controller;
        $this->request= $requestStack->getCurrentRequest();
        $this->tokenStorage=$tokenStorage;
        $this->session=$session;
        $this->passwordHasher=$passwordHasher;
        $this->entityManager=$entityManager;

    }

    public function deleteAccount()
    {
        $tokenStorage = $this->tokenStorage;
        $user = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser() : null;
        $em = $this->entityManager;
        $request = $this->request;
        $passwordHasher = $this->passwordHasher;
        $session = $this->session;

        if ($user)
        {
            $allUsers = $em->getRepository(User::class)->findAll();

            $myPhotos = $em->getRepository(Photo::class)->findBy([
                'user' => $user
            ]);

            $form = $this->createForm(DeleteAccountFormType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                foreach ($allUsers as $entity)
                {
                    if ($user->getUsername() == $entity->getUsername())
                    {
                        if ($user->getPassword() == $passwordHasher->isPasswordValid($entity, $form->get('Password')->getData())) {
                            foreach ($myPhotos as $photo) {

                                $em->remove($photo);
                                $em->flush();
                            }
                            $em->remove($user);
                            $em->flush();

                            $tokenStorage->setToken(null);
                            $session->invalidate();


                            return true;
                        }else{
                            $this->addFlash('accountDeleteError','Wrong password, you need to authenticate to proceed');
                            return false;
                        }

                    }
                }
            }
        }
    }

    public function changePassword()
    {

    }

}