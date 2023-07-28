<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\ConsulType;
use App\Entity\Consultation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConsultationController extends AbstractController
{
    /**
     * @Route("/consultation", name="app_consultation")
     */
    public function index(): Response
    {
        return $this->render('consultation/index.html.twig', [
           
        ]);
    }

    /**
     * @Route("/consultation/{matricule}/nouveau", name="new_consul")
     */
    public function nouvelleConsultation(?String $matricule,Request $request,ManagerRegistry $doctrine){
        $patient= $doctrine->getRepository(Patient::class)->findOneBy(["matricule"=>$matricule]);
        $consul = new Consultation();
        $form = $this->createForm(ConsulType::class,$consul);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $consul->setPatient($patient);
            $consul->setCreatedAt(new  \DateTimeImmutable());
            $doctrine->getManager()->persist($consul);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_patient");
        }

        return $this->renderForm('consultation/form.html.twig', [
           'form'=> $form
        ]);
    }
}
