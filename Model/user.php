<?php
class User
{
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $password;
    protected $telephone;
    protected $date_inscription;
    protected $type;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $password,  // Password parameter here
        ?string $telephone = null,
        string $type = 'user'
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;  // Already hashed when passed
        $this->telephone = $telephone;
        $this->date_inscription = new DateTime();
        $this->type = $type;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getTelephone() { return $this->telephone; }
    public function getDateInscription(): DateTime { return $this->date_inscription; }
    public function getType() { return $this->type; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { 
        $this->password = password_hash($password, PASSWORD_BCRYPT); 
    }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setDateInscription(DateTime $date) { $this->date_inscription = $date; }
    public function setType($type) { $this->type = $type; }
    
}