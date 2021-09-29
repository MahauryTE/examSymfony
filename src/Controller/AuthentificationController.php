<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthentificationController extends AbstractController
{
    /**
     * @Route("/", name="app_authentification")
     */
    public function authentification(RegistrationController $registrationController, SecurityController $securityController): Response
    {
        return $this->render('security/index.html.twig');
    }
}
