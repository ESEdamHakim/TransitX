<?php
class User
{
    protected $id;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $password;
    protected $telephone;
    protected $image;
    protected $date_inscription;
    protected $type;
    protected $face_descriptor; // New attribute

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        ?string $telephone = null,
        string $type = 'user',
        ?string $image = 'default.png',
        ?string $face_descriptor = null // Add to constructor
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password; // Assume already hashed before passing
        $this->telephone = $telephone;
        $this->image = $image;
        $this->date_inscription = new DateTime();
        $this->type = $type;
        $this->face_descriptor = $face_descriptor;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getTelephone() { return $this->telephone; }
    public function getImage() { return $this->image; }
    public function getDateInscription(): DateTime { return $this->date_inscription; }
    public function getType() { return $this->type; }
    public function getFaceDescriptor() { return $this->face_descriptor; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setImage($image) { $this->image = $image; }
    public function setDateInscription(DateTime $date) { $this->date_inscription = $date; }
    public function setType($type) { $this->type = $type; }
    public function setFaceDescriptor($face_descriptor) { $this->face_descriptor = $face_descriptor; }
}
