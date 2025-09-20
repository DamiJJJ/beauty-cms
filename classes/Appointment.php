<?php

require_once __DIR__ . '/../config/database.php';

class Appointment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllAppointments() {
        $query = "SELECT a.id, a.client_id, a.treatment_type, a.appointment_date, a.notes, a.price,
                         c.first_name, c.last_name
                  FROM appointments a
                  JOIN clients c ON a.client_id = c.id
                  ORDER BY a.appointment_date DESC";
        return $this->db->fetchAll($query);
    }
    
    public function getAppointmentById($id) {
        $query = "SELECT a.id, a.client_id, a.treatment_type, a.appointment_date, a.notes, a.price,
                         c.first_name, c.last_name
                  FROM appointments a
                  JOIN clients c ON a.client_id = c.id
                  WHERE a.id = ?";
        return $this->db->fetchAll($query, [$id])[0] ?? null;
    }

    public function addAppointment($data) {
        $query = "INSERT INTO appointments (client_id, treatment_type, appointment_date, notes, price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($query);
        $result = $stmt->execute([
            $data['client_id'],
            $data['treatment_type'],
            $data['appointment_date'],
            $data['notes'],
            $data['price']
        ]);
        return $result ? $this->db->getConnection()->lastInsertId() : false;
    }

    public function updateAppointment($id, $data) {
        $query = "UPDATE appointments SET client_id = ?, treatment_type = ?, appointment_date = ?, notes = ?, price = ? WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        return $stmt->execute([
            $data['client_id'],
            $data['treatment_type'],
            $data['appointment_date'],
            $data['notes'],
            $data['price'],
            $id
        ]);
    }

    public function deleteAppointment($id) {
        $query = "DELETE FROM appointments WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        return $stmt->execute([$id]);
    }

    public function validateAppointmentData($data) {
        $errors = [];
        if (empty($data['client_id'])) {
            $errors[] = "Client selection is required.";
        }
        if (empty($data['appointment_date'])) {
            $errors[] = "Appointment date and time are required.";
        }
        if (empty($data['treatment_type'])) {
            $errors[] = "Treatment type is required.";
        }
        if (!is_numeric($data['price']) || $data['price'] < 0) {
            $errors[] = "Price must be a positive number.";
        }
        return $errors;
    }
}