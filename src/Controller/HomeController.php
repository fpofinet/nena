<?php

namespace App\Controller;

use App\Entity\Consultation;
use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Patient;
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
        $faker = Factory::create();
        $patients=$doctrine->getRepository(Patient::class)->findAll();
        foreach($patients as $p){
            for($i=0;$i<10;$i++){
                $consul= new Consultation();
                $consul->setPatient($p);
                $consul->setCreatedAt(new  \DateTimeImmutable());
                $consul->setDiagnostic($faker->paragraph());
                $doctrine->getManager()->persist($consul);
            }
            $doctrine->getManager()->flush();
        }
        //$doctrine->getManager()->flush();
        /*$faker = Factory::create();
        for ($i=0;$i<20;$i++){
            $patient = new Patient();
            $patient->setNom($faker->lastName());
            $patient->setPrenom($faker->firstName());
            $patient->setContact($faker->phoneNumber());
            $patient->setPoids(rand(12,150));
            $patient->setAge(rand(5,100));
            $patient->setMatricule($this->generateMatricule());
            $patient->setAddedAt(new \DateTimeImmutable());
            if($i%2==0){
                $patient->setSexe("M");
            } else{
                $patient->setSexe("F");
            }
            $patient->setAdresse($faker->address());
            $doctrine->getManager()->persist($patient);
            
        }*/
        
       /* $user = new User();
        $user->setUsername("admin1");
        //$user->setRoles([])
        $hash= $encoder->hashPassword($user,"123456");
        $user->setPassword($hash);
        $doctrine->getManager()->persist($user);*/
        $doctrine->getManager()->flush();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'test',
        ]);
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
