<?php

namespace App\Controller;

use App\Entity\Item;
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
     * @Route("/ordonnance/{matricule}/patient", name="app_ordonnance")
     */
    public function index(?string $matricule,ManagerRegistry $doctrine): Response
    {
        $patient= $doctrine->getRepository(Patient::class)->findOneBy(["matricule"=>$matricule]);
        $ordo=$doctrine->getRepository(Ordonnance::class)->findBy(["patient"=>$patient]);
        return $this->render('ordonnance/index.html.twig', [
            'ordonnances'=>$ordo,
            'matricule'=>$patient->getMatricule()
        ]);
    }
     /**
     * @Route("/ordonnance/{id}/details", name="details_ordonnance")
     */
    public function details(?int $id,ManagerRegistry $doctrine): Response
    {
        $ordo=$doctrine->getRepository(Ordonnance::class)->findOneBy(["id"=>$id]);
        return $this->render('ordonnance/details.html.twig', [
            'ordonnance'=>$ordo,
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

    /**
     * @Route("/ordonnance/{id}/update",name="update_ordo")
     */
    public function updateOrdo(?int $id,ManagerRegistry $doctrine, Request $request):Response
    {
        $ordo= $doctrine->getRepository(Ordonnance::class)->findOneBy(["id"=>$id]);
        $form= $this->createForm(OrdonanceType::class,$ordo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $doctrine->getManager()->persist($ordo);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("details_ordonnance",["id"=>$id]);
        }
        return $this->renderForm('ordonnance/form.html.twig', [
            'form'=>$form,
            'editState'=>$ordo->getId() !==null
        ]);
    }

     /**
     * @Route("/ordonnance/{id}/remove/item",name="rm_item")
     */
    public function removeItem(?int $id,ManagerRegistry $doctrine):Response
    {
        $item= $doctrine->getRepository(Item::class)->findOneBy(["id"=>$id]);
        $id=$item->getOrdonnance()->getId();
        //dd($id);
        $doctrine->getManager()->remove($item);
        $doctrine->getManager()->flush();
        
        return $this->redirectToRoute("details_ordonnance",["id"=>$id]);
    }
}
