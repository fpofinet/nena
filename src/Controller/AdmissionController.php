<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Service\AdmissionService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdmissionController extends AbstractController
{
    /**
     * @Route("/admission", name="app_admission")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $patients=$doctrine->getRepository(Patient::class)->findAll();
        return $this->render('admission/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/admission/queue", name="queue")
     */
    public function renderQueue(AdmissionService $service):Response
    {
        dd($service->GetQueue());
        return $this->render('admission/list.html.twig', [
            'queue' => $service->GetQueue(),
        ]);
    }
    /**
     * @Route("/admission/{matricule}/ajouter", name="add_admission")
     */
    public function addAdmission($matricule,ManagerRegistry $doctrine,AdmissionService $service): Response
    {

        $patient=$doctrine->getRepository(Patient::class)->findOneBy(["matricule"=>$matricule]);
        $service->addQueue($patient);
        return $this->redirectToRoute("app_admission");
    }

     /**
     * @Route("/admission/{matricule}/retirer", name="rm_admission")
     */
    public function removeAdmission($matricule,ManagerRegistry $doctrine,AdmissionService $service): Response
    {
        
        $patient=$doctrine->getRepository(Patient::class)->findOneBy(["matricule"=>$matricule]);
        $service->removeQueue($patient);
        return $this->redirectToRoute("app_admission");
    }


}
