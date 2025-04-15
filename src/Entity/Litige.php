<?php

namespace App\Entity;

use App\Repository\LitigeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LitigeRepository::class)]
class Litige
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\ManyToOne]
    private User $User;

    #[ORM\ManyToOne]
    private Covoiturage $Covoiturage;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getUser(): User
    {
        return $this->User;
    }

    public function addUser(User $user): static
    {
       $this->User = $user;
        return $this;
    }

    public function getCovoiturage(): Covoiturage
    {
        return $this->Covoiturage;
    }

    public function addCovoiturage(Covoiturage $covoiturage): static
    {
        $this->Covoiturage = $covoiturage;

        return $this;
    }

}