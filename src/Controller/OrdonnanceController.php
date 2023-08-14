<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Ordonnance;
use App\Form\OrdonanceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrdonnanceController extends AbstractController
{
    /**
     * @Route("/ordonnance/{matricule}/details", name="app_ordonnance")
     */
    public function index(?string $matricule,ManagerRegistry $doctrine): Response
    {
        $patient= $doctrine->getRepository(Patient::class)->findOneBy(["matricule"=>$matricule]);
        $ordo=$doctrine->getRepository(Ordonnance::class)->findOneBy(["patient"=>$patient]);
        return $this->render('ordonnance/index.html.twig', [
            'ordonnance'=>$ordo,
            'matricule'=>$patient->getMatricule()
        ]);
    }
    /**
     * @Route("/ordonnance/{matricule}/patient/new",name="new_ordo")
     */
    public function newOrdo(?string $matricule,ManagerRegistry $doctrine, Request $request):Response
    {
        $patient= $doctrine->getRepository(Patient::class)->findOneBy(["matricule"=>$matricule]);
        $ordo= new Ordonnance();
        $form= $this->createForm(OrdonanceType::class,$ordo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form);
            if($ordo->getId()== null){
                $ordo->setPatient($patient);      
                $ordo->setMedecin($this->getUser());
                $ordo->setCreatedAt(new \DateTimeImmutable());
            }
            $doctrine->getManager()->persist($ordo);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_ordonnance",["matricule"=>$matricule]);
        }
        return $this->renderForm('ordonnance/form.html.twig', [
            'form'=>$form,
            'editState'=>$ordo->getId() !==null
        ]);
    }
}
