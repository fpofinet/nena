<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Patient;
use App\Form\PatientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatientController extends AbstractController
{
    /**
     * @Route("/patient", name="app_patient")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $patients =$doctrine->getRepository(Patient::class)->findAll();
        return $this->render('patient/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/patient/nouveau", name="nouveau_patient")
     */

    public function nouveauPatient(Request $request,ManagerRegistry $doctrine){
        $patient = new Patient();
        $form =$this->createForm(PatientType::class,$patient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $patient->setAddedAt(new \DateTimeImmutable());
            $patient->setMatricule($this->generateMatricule());
           // dd($patient);
            $doctrine->getManager()->persist($patient);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_patient");
        }

        return $this->renderForm('patient/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/patient/{id}/modifier", name="modifier_patient")
     */
    public function modifierPatient(Patient $patient,Request $request,ManagerRegistry $doctrine){

        $form =$this->createForm(PatientType::class,$patient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $doctrine->getManager()->persist($patient);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_patient");
        }

        return $this->renderForm('patient/form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/patient/{id}/delete", name="delete_patient")
     */
    public function deletePatient(Patient $patient,ManagerRegistry $doctrine){
        if($patient != null){
            $doctrine->getManager()->remove($patient);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_patient");
        }
        return $this->redirectToRoute("app_patient");
    }

    private function generateMatricule($length = 4, $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $date= new DateTimeImmutable();
        $matricule=$randomString.''.$date->format("dmy");
        return $matricule;
    }
}
