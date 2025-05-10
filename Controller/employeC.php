<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/employe.php';
require_once __DIR__ . '/../models/user.php';

class EmployeC {
    // List all employees
    public function listEmployes() {
        $sql = "SELECT u.*, e.date_embauche, e.poste, e.salaire, e.role 
                FROM user u
                JOIN employe e ON u.id = e.user_id
                WHERE u.type = 'employe'";
        $db = config::getConnexion();
        try {
            $stmt = $db->query($sql);
            $employes = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $employe = new Employe(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    new DateTime($row['date_embauche']),
                    $row['poste'],
                    $row['salaire'],
                    $row['role'],
                    $row['telephone']
                );
                $employe->setId($row['id']);
                $employe->setDateInscription(new DateTime($row['date_inscription']));
                $employes[] = $employe;
            }
            return $employes;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Show a single employee
    public function showEmploye($id) {
        $sql = "SELECT u.*, e.date_embauche, e.poste, e.salaire, e.role 
                FROM user u
                JOIN employe e ON u.id = e.user_id
                WHERE u.id = :id AND u.type = 'employe'";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) return null;
            
            $employe = new Employe(
                $row['nom'],
                $row['prenom'],
                $row['email'],
                new DateTime($row['date_embauche']),
                $row['poste'],
                $row['salaire'],
                $row['role'],
                $row['telephone']
            );
            $employe->setId($row['id']);
            $employe->setDateInscription(new DateTime($row['date_inscription']));
            
            return $employe;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Add an employee (alternative to UserC's addUser for employee-specific operations)
    public function addEmploye(Employe $employe) {
        $db = config::getConnexion();
        
        try {
            $db->beginTransaction();
            
            // Insert into user table
            $sql = "INSERT INTO user (nom, prenom, email, telephone, date_inscription, type)
                    VALUES (:nom, :prenom, :email, :telephone, :date_inscription, 'employe')";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $employe->getNom(),
                ':prenom' => $employe->getPrenom(),
                ':email' => $employe->getEmail(),
                ':telephone' => $employe->getTelephone(),
                ':date_inscription' => $employe->getDateInscription()->format('Y-m-d H:i:s')
            ]);
            
            $userId = $db->lastInsertId();
            
            // Insert into employe table
            $sql = "INSERT INTO employe (user_id, date_embauche, poste, salaire, role)
                    VALUES (:user_id, :date_embauche, :poste, :salaire, :role)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':date_embauche' => $employe->getDateEmbauche()->format('Y-m-d'),
                ':poste' => $employe->getPoste(),
                ':salaire' => $employe->getSalaire(),
                ':role' => $employe->getRole()
            ]);
            
            $db->commit();
            return $userId;
        } catch (Exception $e) {
            $db->rollBack();
            die('Error:' . $e->getMessage());
        }
    }

    // Update an employee
    public function updateEmploye(Employe $employe) {
        $db = config::getConnexion();
        
        try {
            $db->beginTransaction();
            
            // Update user table
            $sql = "UPDATE user SET 
                    nom = :nom, 
                    prenom = :prenom, 
                    email = :email, 
                    telephone = :telephone
                    WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':id' => $employe->getId(),
                ':nom' => $employe->getNom(),
                ':prenom' => $employe->getPrenom(),
                ':email' => $employe->getEmail(),
                ':telephone' => $employe->getTelephone()
            ]);
            
            // Update employe table
            $sql = "UPDATE employe SET 
                    date_embauche = :date_embauche,
                    poste = :poste,
                    salaire = :salaire,
                    role = :role
                    WHERE user_id = :user_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':user_id' => $employe->getId(),
                ':date_embauche' => $employe->getDateEmbauche()->format('Y-m-d'),
                ':poste' => $employe->getPoste(),
                ':salaire' => $employe->getSalaire(),
                ':role' => $employe->getRole()
            ]);
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            die('Error:' . $e->getMessage());
        }
    }

    // Delete an employee
    public function deleteEmploye($id) {
        // Since we have ON DELETE CASCADE, deleting from user will delete from employe
        $sql = "DELETE FROM user WHERE id = :id AND type = 'employe'";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        try {
            return $stmt->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Search employees by position
    public function searchByPoste($poste) {
        $sql = "SELECT u.*, e.date_embauche, e.poste, e.salaire, e.role 
                FROM user u
                JOIN employe e ON u.id = e.user_id
                WHERE u.type = 'employe' AND e.poste LIKE :poste";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':poste', "%$poste%");
        
        try {
            $stmt->execute();
            $employes = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $employe = new Employe(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    new DateTime($row['date_embauche']),
                    $row['poste'],
                    $row['salaire'],
                    $row['role'],
                    $row['telephone']
                );
                $employe->setId($row['id']);
                $employe->setDateInscription(new DateTime($row['date_inscription']));
                $employes[] = $employe;
            }
            return $employes;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // Get employees by role
    public function getByRole($role) {
        $sql = "SELECT u.*, e.date_embauche, e.poste, e.salaire, e.role 
                FROM user u
                JOIN employe e ON u.id = e.user_id
                WHERE u.type = 'employe' AND e.role = :role";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':role', $role);
        
        try {
            $stmt->execute();
            $employes = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $employe = new Employe(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    new DateTime($row['date_embauche']),
                    $row['poste'],
                    $row['salaire'],
                    $row['role'],
                    $row['telephone']
                );
                $employe->setId($row['id']);
                $employe->setDateInscription(new DateTime($row['date_inscription']));
                $employes[] = $employe;
            }
            return $employes;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
}