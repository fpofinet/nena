<?php

namespace App\Service;

use App\Entity\Patient;
use App\Entity\Admission;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class AdmissionService
{
    private $manager;

    /*
     * Cette methode permet est le constructeur de notre
     */
    public function __construct(ManagerRegistry $manager)
    {
        $this->manager = $manager;
    }


    /*
     * Cette methode permet d'ajouter un patient à la file d'attente
     */

    public function addQueue(?Patient $patient)
    {
        $admission =new Admission();
        $admission->setPatient($patient->getMatricule());
        $admission->setCreatedAT(new  \DateTime());
        $this->manager->getManager()->persist($admission);
        $this->manager->getManager()->flush();
    }

    /*
     * Cette methode permet de retirer un patient dans la file d'attente
     */

    public function removeQueue(?Patient $patient)
    {
        
        $admissions=$this->manager->getRepository(Admission::class)->findAll();
        foreach ($admissions as $admission) {
            if($admission->getPatient() !=$patient->getMatricule()){
                $this->manager->getManager()->remove($admission);
                $this->manager->getManager()->flush();
            }
        }
    }
    /*
     * Cette methode permet de retirer un patient dans la file d'attente
     */

    public function GetQueue()
    {
        $admissions=$this->manager->getRepository(Admission::class)->findAll();
        $patients=array();
        foreach ($admissions as $admission) {
            $patients[]=$this->manager->getRepository(Patient::class)->findOneBy(["matricule"=>$admission->getPatient()]);
        }
        return $patients;
    }
}
