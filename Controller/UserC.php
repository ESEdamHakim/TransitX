<?php
require_once __DIR__ . '/../Model/user.php';
require_once __DIR__ . '/../Model/client.php';
require_once __DIR__ . '/../Model/employe.php';
require_once __DIR__ . '/../config.php';

class UserC {
    public function listUsers($sort = 'id', $order = 'ASC', $search = '') {
        try {
            $sql = "SELECT u.*, 
                    c.date_naissance,
                    e.date_embauche, e.poste, e.salaire, e.role
                    FROM user u
                    LEFT JOIN client c ON u.id = c.user_id AND u.type = 'client'
                    LEFT JOIN employe e ON u.id = e.user_id AND u.type = 'employe'";
            
            // Add search condition
            if (!empty($search)) {
                $sql .= " WHERE (u.nom LIKE :search OR u.prenom LIKE :search OR u.email LIKE :search)";
            }
            
            // Validate sort column to prevent SQL injection
            $allowedSortColumns = ['id', 'nom', 'prenom', 'email', 'type', 'date_inscription'];
            $sort = in_array($sort, $allowedSortColumns) ? $sort : 'id';
            
            // Validate order direction
            $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
            
            $sql .= " ORDER BY u.$sort $order";
            
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            
            if (!empty($search)) {
                $searchTerm = "%$search%";
                $query->bindValue(':search', $searchTerm, PDO::PARAM_STR);
            }
            
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            
            $users = [];
            foreach ($results as $row) {
                if ($row['type'] === 'client') {
                    $user = new Client(
                        $row['nom'],
                        $row['prenom'],
                        $row['email'],
                        $row['password'],
                        $row['telephone'],
                        $row['date_naissance'] ? new DateTime($row['date_naissance']) : null
                    );
                } else {
                    $user = new Employe(
                        $row['nom'],
                        $row['prenom'],
                        $row['email'],
                        $row['password'],
                        $row['date_embauche'] ? new DateTime($row['date_embauche']) : new DateTime(),
                        $row['poste'] ?? '',
                        $row['salaire'] ?? 0,
                        $row['role'] ?? '',
                        $row['telephone']
                    );
                }
                $user->setId($row['id']);
                $user->setDateInscription(new DateTime($row['date_inscription']));
                $users[] = $user;
            }
            
            return $users;
            
        } catch (Exception $e) {
            error_log('Error in listUsers: ' . $e->getMessage());
            return [];
        }
    }

    public function deleteUser(int $id): bool {
        $sql = "DELETE FROM user WHERE id = :id";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        try {
            return $stmt->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    public function addUser(User $user): bool {
        $db = config::getConnexion();
    
        try {
            $db->beginTransaction();
    
            // Insert into user table with password
            $sql = "INSERT INTO user (nom, prenom, email, password, telephone, date_inscription, type)
                    VALUES (:nom, :prenom, :email, :password, :telephone, :date_inscription, :type)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(), // Hashed password
                ':telephone' => $user->getTelephone(),
                ':date_inscription' => $user->getDateInscription()->format('Y-m-d H:i:s'),
                ':type' => $user->getType()
            ]);
    
            $userId = $db->lastInsertId();
    
            // Insert into specific table based on type
            if ($user instanceof Client) {
                $sql = "INSERT INTO client (user_id, date_naissance)
                        VALUES (:user_id, :date_naissance)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userId,
                    ':date_naissance' => $user->getDateNaissance() ? $user->getDateNaissance()->format('Y-m-d') : null
                ]);
            } elseif ($user instanceof Employe) {
                $sql = "INSERT INTO employe (user_id, date_embauche, poste, salaire, role)
                        VALUES (:user_id, :date_embauche, :poste, :salaire, :role)";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $userId,
                    ':date_embauche' => $user->getDateEmbauche()->format('Y-m-d'),
                    ':poste' => $user->getPoste(),
                    ':salaire' => $user->getSalaire(),
                    ':role' => $user->getRole()
                ]);
            }
    
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            die('Error:' . $e->getMessage());
        }
    }
    

    public function showUser(int $id): ?User {
        $sql = "SELECT u.*, 
                c.date_naissance,
                e.date_embauche, e.poste, e.salaire, e.role
                FROM user u
                LEFT JOIN client c ON u.id = c.user_id AND u.type = 'client'
                LEFT JOIN employe e ON u.id = e.user_id AND u.type = 'employe'
                WHERE u.id = :id";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$row) return null;
    
            if ($row['type'] === 'client') {
                $user = new Client(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'], // Add password
                    $row['telephone'],
                    $row['date_naissance'] ? new DateTime($row['date_naissance']) : null
                );
            }else {
                $user = new Employe(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'] ?? '',
                    $row['date_embauche'] ? new DateTime($row['date_embauche']) : new DateTime(),
                    $row['poste'] ?? '',
                    $row['salaire'] ?? 0,
                    $row['role'] ?? '',
                    $row['telephone']
                );
            }
            $user->setId($row['id']);
            $user->setDateInscription(new DateTime($row['date_inscription']));
            $user->setPassword($row['password']); // Store hashed password
    
            return $user;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

    public function updateUser(User $user): bool {
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
                ':id' => $user->getId(),
                ':nom' => $user->getNom(),
                ':prenom' => $user->getPrenom(),
                ':email' => $user->getEmail(),
                ':telephone' => $user->getTelephone()
            ]);
            
            // Update specific table based on type
            if ($user instanceof Client) {
                $sql = "UPDATE client SET 
                        date_naissance = :date_naissance
                        WHERE user_id = :user_id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $user->getId(),
                    ':date_naissance' => $user->getDateNaissance() ? $user->getDateNaissance()->format('Y-m-d') : null
                ]);
            } elseif ($user instanceof Employe) {
                $sql = "UPDATE employe SET 
                        date_embauche = :date_embauche,
                        poste = :poste,
                        salaire = :salaire,
                        role = :role
                        WHERE user_id = :user_id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':user_id' => $user->getId(),
                    ':date_embauche' => $user->getDateEmbauche()->format('Y-m-d'),
                    ':poste' => $user->getPoste(),
                    ':salaire' => $user->getSalaire(),
                    ':role' => $user->getRole()
                ]);
            }
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            die('Error:' . $e->getMessage());
        }
    }

    public function getUserByEmail(string $email): ?User {
        $sql = "SELECT u.*, 
                c.date_naissance,
                e.date_embauche, e.poste, e.salaire, e.role
                FROM user u
                LEFT JOIN client c ON u.id = c.user_id AND u.type = 'client'
                LEFT JOIN employe e ON u.id = e.user_id AND u.type = 'employe'
                WHERE u.email = :email";
        
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
    
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$row) return null;
    
            if ($row['type'] === 'client') {
                $user = new Client(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'], // Make sure this is hashed
                    $row['telephone'],
                    $row['date_naissance'] ? new DateTime($row['date_naissance']) : null
                );
            } else {
                $user = new Employe(
                    $row['nom'],
                    $row['prenom'],
                    $row['email'],
                    $row['password'],
                    $row['date_embauche'] ? new DateTime($row['date_embauche']) : new DateTime(),
                    $row['poste'],
                    $row['salaire'],
                    $row['role'],
                    $row['telephone']
                );
            }
            
            $user->setId($row['id']);
            return $user;
            
        } catch (Exception $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return null;
        }
    }

    // Remove the duplicate listUsers method and listUsersWithFilters method
}

