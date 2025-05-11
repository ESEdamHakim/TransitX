<?php
require_once 'user.php';

class Employe extends User
{
    private $date_embauche;
    private $poste;
    private $salaire;
    private $role;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        DateTime $date_embauche,
        string $poste,
        float $salaire,
        string $role,
        ?string $telephone = null
    ) {
        parent::__construct($nom, $prenom, $email, $password, $telephone, 'employe');
        $this->date_embauche = $date_embauche;
        $this->poste = $poste;
        $this->salaire = $salaire;
        $this->role = $role;
    }

    // Getters
    public function getDateEmbauche(): DateTime { return $this->date_embauche; }
    public function getPoste(): string { return $this->poste; }
    public function getSalaire(): float { return $this->salaire; }
    public function getRole(): string { return $this->role; }

    // Setters
    public function setDateEmbauche(DateTime $date): void { $this->date_embauche = $date; }
    public function setPoste(string $poste): void { $this->poste = $poste; }
    public function setSalaire(float $salaire): void { $this->salaire = $salaire; }
    public function setRole(string $role): void { $this->role = $role; }
}
