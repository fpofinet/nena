<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Form\CreateUserType;
use App\Repository\UserRepository;
use App\Repository\PatientRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ConsultationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(UserRepository $repo): Response
    {
        $users=$repo->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/create",name="new_user")
     *
     */
    public function createUser(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $encoder):Response
    {
       
        $user = new User();
        $form= $this->createForm(UserType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setNom($form->getData()["nom"]);
            $user->setPrenom($form->getData()["prenom"]);
            $user->setRoles([$form->getData()["roles"]]);
            $user->setContact($form->getData()["contact"]);
            $user->setUsername($user->getNom());
            if($form->getData()["roles"]=="ROLE_MEDECIN"){
                $user->setUsername("dr".$user->getNom());
            }
            
            $hash= $encoder->hashPassword($user,$form->getData()["password"]);
            $user->setPassword($hash);
           
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute("app_admin");
        }
        return $this->renderForm('admin/form.html.twig',[
            'form'=>$form
        ]);
    }

    /**
     * @Route("/admin/user/{id}/update",name="update_user")
     */
    public function updateUser(?int $id,ManagerRegistry $doctrine,Request $request):Response
    {
        if($id){
            $user=$doctrine->getRepository(User::class)->findOneBy(["id"=>$id]);
        }
        $form= $this->createForm(CreateUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute("app_admin");
        }
        return $this->renderForm('admin/form.html.twig',[
            'form'=>$form
        ]);
    }

    
    /**
     * @Route("/admin/stats",name="stats")
     */

    public function stats(ManagerRegistry $doctrine):Response
    {
        $patientRepo= new PatientRepository($doctrine);
        $repo= new ConsultationRepository($doctrine);
        $consulRepo= new ConsultationRepository($doctrine);
        $date=new DateTimeImmutable();
        return $this->renderForm('admin/stats.html.twig',[
            'date'=>$date,
            'totalPatient'=>$patientRepo->count([]),
            'totalConsul'=>$consulRepo->count([]),
            'patientDM'=>$patientRepo->getCurrentMonthCount(),
            'consulDM'=>$consulRepo->getCurrentMonthCounnt(),
        ]);
    }
    /**
     * @Route("/admin/stats/par-mois",name="stats_pm")
     */

    public function statsPM(ManagerRegistry $doctrine):Response
    { 
        $repo= new ConsultationRepository($doctrine);
        $data=$repo->statistique();
        return $this->renderForm('admin/totalPM.html.twig',[
            'datas'=>$data,  
        ]);
    }
}
