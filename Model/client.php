<?php
require_once 'user.php';

class Client extends User
{
    private $date_naissance;
    // In Client.php
    public function __construct($nom, $prenom, $email, $password, $telephone = null, $date_naissance = null, $image = 'default.png')
    {
        parent::__construct($nom, $prenom, $email, $password, $telephone, 'client', $image);
        $this->date_naissance = $date_naissance;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getDateNaissance()
    {
        return $this->date_naissance;
    }
    public function setDateNaissance($date)
    {
        $this->date_naissance = $date;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setFaceDescriptor($desc)
    {
        $this->face_descriptor = $desc;
    }
    public function getFaceDescriptor()
    {
        return $this->face_descriptor;
    }
}