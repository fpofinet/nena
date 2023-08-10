<?php

namespace App\Entity;

use App\Repository\AdmissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdmissionRepository::class)
 */
class Admission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $patient;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?string
    {
        return $this->patient;
    }

    public function setPatient(string $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getCreatedAT(): ?\DateTimeInterface
    {
        return $this->createdAT;
    }

    public function setCreatedAT(\DateTimeInterface $createdAT): self
    {
        $this->createdAT = $createdAT;

        return $this;
    }
}
