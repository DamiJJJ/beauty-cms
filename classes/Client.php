<?php
require_once __DIR__ . '/../config/database.php';

class Client {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllClients() {
        $query = "SELECT id, first_name, last_name, phone, email, notes, created_at, 
              (SELECT COUNT(*) FROM appointments WHERE client_id = clients.id) as appointments_count 
              FROM clients ORDER BY created_at DESC";
        return $this->db->fetchAll($query);
    }
    
    public function addClient($data) {
    $query = "INSERT INTO clients (first_name, last_name, phone, email, birth_date, notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->getConnection()->prepare($query);
    $stmt->execute([
        $data['first_name'],
        $data['last_name'],
        $data['phone'],
        $data['email'],
        $data['birth_date'],
        $data['notes']
    ]);
    return $this->db->getConnection()->lastInsertId();
}

public function getClientById($id) {
    $query = "SELECT id, first_name, last_name, phone, email, notes, created_at, 
              (SELECT COUNT(*) FROM appointments WHERE client_id = clients.id) as appointments_count 
              FROM clients WHERE id = ?";
    return $this->db->fetchAll($query, [$id])[0] ?? null;
}

public function updateClient($id, $data) {
    $query = "UPDATE clients SET first_name = ?, last_name = ?, phone = ?, email = ?, birth_date = ?, notes = ? WHERE id = ?";
    $stmt = $this->db->getConnection()->prepare($query);
    $result = $stmt->execute([
        $data['first_name'],
        $data['last_name'],
        $data['phone'],
        $data['email'],
        $data['birth_date'],
        $data['notes'],
        $id
    ]);
    return $result;
}

public function deleteClient($id) {
    $query = "DELETE FROM clients WHERE id = ?";
    $stmt = $this->db->getConnection()->prepare($query);
    return $stmt->execute([$id]);
}

public function searchClients($searchTerm) {
    $term = "%" . $searchTerm . "%";
    $query = "SELECT id, first_name, last_name, phone, email, notes, created_at,
              (SELECT COUNT(*) FROM appointments WHERE client_id = clients.id) as appointments_count
              FROM clients WHERE first_name LIKE ? OR last_name LIKE ? OR phone LIKE ? ORDER BY created_at DESC";
    return $this->db->fetchAll($query, [$term, $term, $term]);
}

public function validateClientData($data) {
    $errors = [];
    if (empty($data['first_name'])) {
        $errors[] = "Imię jest wymagane.";
    }
    if (empty($data['last_name'])) {
        $errors[] = "Nazwisko jest wymagane.";
    }
    return $errors;
}

public function countAllClients() {
        $query = "SELECT COUNT(*) FROM clients";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getRecentClients($limit = 5) {
        $query = "SELECT id, first_name, last_name, created_at FROM clients ORDER BY created_at DESC LIMIT ?";
        return $this->db->fetchAll($query, [$limit]);
    }
}