<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            //return $this->redirectToRoute('app_patient');
            if (in_array('ROLE_RECEPTIONISTE', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('reception_home');
            } else if (in_array('ROLE_MEDECIN',  $this->getUser()->getRoles())) {
                return $this->redirectToRoute('medecin_home');
            }else if (in_array('ROLE_ADMIN',  $this->getUser()->getRoles())) {
                return $this->redirectToRoute('app_admin');
            }else{
                return $this->redirectToRoute('app_logout');
            }
        }
    
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
