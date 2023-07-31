<?php

namespace App\Service;

use App\Entity\Patient;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\RequestStack;

class AdmissionService
{
    private $requestStack;
    private $queue = array();

    /*
     * Cette methode permet d'initialliser avec la session et la file d'attente
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        //$this->requestStack->getSession()->set('queue', $this->queue);
        //$session->set('cart',$this->queue);

    }


    /*
     * Cette methode permet d'ajouter un patient à la file d'attente
     */

    public function addQueue(?Patient $patient)
    {
        $session=$this->requestStack->getSession();
        if($session->get('queue')!=null){
           
            $this->queue=$session->get('queue');
            $this->queue[]=$patient;
            $session->set('queue', $this->queue);

        } else{
            $this->queue[]=$patient;
            $session->set('queue',$this->queue);
        }
       
    }

    /*
     * Cette methode permet de retirer un patient dans la file d'attente
     */

    public function removeQueue(?Patient $patient)
    {
        $queue=$this->GetQueue();
        $nqueue=array();
        foreach ($queue as $q) {
            if($q->getId() !=$patient->getId()){
                $nqueue[]=$q;
            }
        }
        //$queue->removeElement($patient);
        $this->requestStack->getSession()->set('queue', $nqueue);
    }
    /*
     * Cette methode permet de retirer un patient dans la file d'attente
     */

    public function GetQueue()
    {
        return $this->requestStack->getSession()->get('queue');;
    }
}
