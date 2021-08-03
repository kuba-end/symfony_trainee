<?php

namespace App\Controller;

use App\Form\DeleteAccountFormType;
use App\Service\AccountSettingsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(AccountSettingsService $accountSettingsService): Response
    {
        if($accountSettingsService->deleteAccount())
        {
            $this->addFlash('success','Your account is deleted');
            return $this->redirectToRoute("index");

        }


        return $this->render('delete_account/index.html.twig', [
            'deleteAccountForm' => $this->createForm(DeleteAccountFormType::class)->createView()
        ]);
    }
}