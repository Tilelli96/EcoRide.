<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: "preferences")]
class Preferences
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: "string")]
    private $userId;

    #[MongoDB\Field(type: "bool")]
    private $musique;

    #[MongoDB\Field(type: "bool")]
    private $climatisation;

    #[MongoDB\Field(type: "bool")]
    private $animauxAcceptes;

    #[MongoDB\Field(type: "collection")]
    private array $autresPreferences = [];

    public function getId(): ?string 
    { 
        return $this->id; 
    }

    public function getUserId(): ?string 
    { 
        return $this->utilisateurId; 
    }

    public function setUserId(string $userId): void 
    { 
        $this->userId = $userId; 
    }

    public function getMusique(): ?bool 
    { 
        return $this->musique; 
    }
    public function setMusique(bool $musique): void 
    { 
        $this->musique = $musique; 
    }

    public function getClimatisation(): ?bool
    {
        return $this->climatisation; 
    }
    public function setClimatisation(string $clim): void 
    {
        $this->climatisation = $clim; 
    }

    public function getAnimauxAcceptes(): ?bool 
    {
        return $this->animauxAcceptes; 
    }

    public function setAnimauxAcceptes(bool $acceptes): void 
    {
        $this->animauxAcceptes = $acceptes; 
    }

    public function getAutresPreferences(): array 
    {
        return $this->autresPreferences; 
    }

    public function addAutrePreference(?string $preference): void
    {
        if($preference === null){
            return;
        }
        if (!in_array($preference, $this->autresPreferences)) {
            $this->autresPreferences[] = $preference;
        }
    }
}