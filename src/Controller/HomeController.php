<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AdmissionService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }

    /**
     * @Route("/medecin", name="medecin_home")
     */
    public function doc(AdmissionService $service): Response
    {
        $patients=$service->GetQueue();
        return $this->render('home/medecin.html.twig', [
            'patients' => $patients,
        ]);
    }

     /**
     * @Route("/reception", name="reception_home")
     */
    public function rec(ManagerRegistry $doctrine): Response
    {
        return $this->render('home/reception.html.twig', [
        ]);
    }

     /**
     * @Route("/dump", name="dump")
     */
    public function dump(ManagerRegistry $doctrine,UserPasswordHasherInterface $encoder)
    {
        $user = new User();
        $user->setUsername("admin1");
        //$user->setRoles([])
        $hash= $encoder->hashPassword($user,"123456");
        $user->setPassword($hash);
        $doctrine->getManager()->persist($user);
        $doctrine->getManager()->flush();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'test',
        ]);
    }


}
