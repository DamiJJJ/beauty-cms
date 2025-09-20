<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Appointment.php';
require_once __DIR__ . '/../classes/Client.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$appointmentObj = new Appointment();
$clientObj = new Client();

try {
    switch ($method) {
        case 'GET':
            handleGetRequest($appointmentObj, $clientObj);
            break;
        case 'POST':
            handlePostRequest($appointmentObj);
            break;
        case 'PUT':
            handlePutRequest($appointmentObj);
            break;
        case 'DELETE':
            handleDeleteRequest($appointmentObj);
            break;
        default:
            sendResponse(false, 'Unsupported HTTP method');
    }
} catch (Exception $e) {
    sendResponse(false, 'Server error: ' . $e->getMessage());
}

function handleGetRequest($appointmentObj, $clientObj) {
    $action = $_GET['action'] ?? '';
    if ($action === 'list') {
        $appointments = $appointmentObj->getAllAppointments();
        sendResponse(true, 'Appointment list', $appointments);
    } elseif ($action === 'get_appointment' && isset($_GET['id'])) {
        $appointment = $appointmentObj->getAppointmentById($_GET['id']);
        sendResponse(true, 'Appointment data', $appointment);
    } elseif ($action === 'clients') {
        $clients = $clientObj->getAllClients();
        sendResponse(true, 'Client list', $clients);
    } else {
        sendResponse(false, 'Unknown action');
    }
}

function handlePostRequest($appointmentObj) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        sendResponse(false, 'No data to process.');
        return;
    }

    $errors = $appointmentObj->validateAppointmentData($data);
    if (!empty($errors)) {
        sendResponse(false, 'Validation errors', $errors);
        return;
    }

    $result = $appointmentObj->addAppointment($data);
    if ($result) {
        sendResponse(true, 'Appointment added successfully');
    } else {
        sendResponse(false, 'Error adding appointment');
    }
}

function handlePutRequest($appointmentObj) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id'])) {
        sendResponse(false, 'Invalid appointment ID');
        return;
    }

    $errors = $appointmentObj->validateAppointmentData($data);
    if (!empty($errors)) {
        sendResponse(false, 'Validation errors', $errors);
        return;
    }

    $result = $appointmentObj->updateAppointment($data['id'], $data);
    if ($result) {
        sendResponse(true, 'Appointment updated successfully');
    } else {
        sendResponse(false, 'Error updating appointment');
    }
}

function handleDeleteRequest($appointmentObj) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id'])) {
        sendResponse(false, 'Invalid appointment ID');
        return;
    }
    $result = $appointmentObj->deleteAppointment($data['id']);
    if ($result) {
        sendResponse(true, 'Appointment deleted successfully');
    } else {
        sendResponse(false, 'Error deleting appointment');
    }
}

function sendResponse($success, $message, $data = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}