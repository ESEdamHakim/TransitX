<?php

include_once dirname(__FILE__) . '/../config.php';
require_once __DIR__ . '/../Model/user.php';
require_once __DIR__ . '/../Model/client.php';
require_once __DIR__ . '/../Model/employe.php';

class ClientC
{
  public function listClients()
  {
    $sql = "SELECT * FROM Client";
    $db = config::getConnexion();
    try {
      $liste = $db->query($sql);
      return $liste;
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }

  function showClient($id)
  {
    $sql = "SELECT * from client where IdClient = $id";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute();

      $client = $query->fetch();
      return $client;
    } catch (Exception $e) {
      die('Error: ' . $e->getMessage());
    }
  }

  function getClientByEmail($email)
  {
    $sql = "SELECT idClient, email, password, firstname, lastname from client where email = :email";
    $db = config::getConnexion();
    try {
      $query = $db->prepare($sql);
      $query->execute([
        ':email' => $email,
      ]);

      $client = $query->fetch();
      return $client;
    } catch (Exception $e) {
      die('Error: ' . $e->getMessage());
    }
  }

  function addClient($client)
  {
    $db = config::getConnexion();

    try {
      $db->beginTransaction();

      // Insert into `user` table with face_descriptor
      $sqlUser = "INSERT INTO user (nom, prenom, email, password, telephone, image, type, face_descriptor) 
                    VALUES (:nom, :prenom, :email, :password, :telephone, :image, 'client', :face_descriptor)";
      $stmtUser = $db->prepare($sqlUser);
      $stmtUser->execute([
        ':nom' => $client->getNom(),
        ':prenom' => $client->getPrenom(),
        ':email' => $client->getEmail(),
        ':password' => $client->getPassword(),
        ':telephone' => $client->getTelephone(),
        ':image' => $client->getImage(),
        ':face_descriptor' => $client->getFaceDescriptor()
      ]);

      $userId = $db->lastInsertId();

      if (!$userId) {
        throw new Exception("User ID generation failed.");
      }

      $sqlClient = "INSERT INTO client (user_id, date_naissance) VALUES (:user_id, :date_naissance)";
      $stmtClient = $db->prepare($sqlClient);
      $stmtClient->execute([
        ':user_id' => $userId,
        ':date_naissance' => $client->getDateNaissance() ? $client->getDateNaissance()->format('Y-m-d') : null,
      ]);

      $db->commit();
      return true;
    } catch (Exception $e) {
      $db->rollBack();
      echo 'Error: ' . $e->getMessage();
      return false;
    }
  }

  function updateClient($client, $id)
  {
    $db = config::getConnexion();
    try {
      $query = $db->prepare(
        "UPDATE client SET
            firstName = :fi, 
            lastName = :la,
            email = :em,
            phone = :p
        WHERE idClient = :id"
      );

      $query->execute([
        'id' => $id,
        'fi' => $client->getFirstName(),
        'la' => $client->getLastName(),
        'em' => $client->getEmail(),
        'p' => $client->getPhone()
      ]);
      $query->rowCount() . " records UPDATED successfully <br>";
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  function deleteClient($id)
  {
    $sql = "DELETE FROM client WHERE idClient = :id";
    $db = config::getConnexion();

    try {
      $req = $db->prepare($sql);
      $req->bindValue(':id', $id);
      $req->execute();
    } catch (Exception $e) {
      die('Error:' . $e->getMessage());
    }
  }
}
