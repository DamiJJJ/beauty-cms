<?php
// ajax/clients.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Client.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$clientObj = new Client();

try {
    switch ($method) {
        case 'GET':
            handleGetRequest($clientObj);
            break;

        case 'POST':
            handlePostRequest($clientObj);
            break;

        case 'PUT':
            handlePutRequest($clientObj);
            break;

        case 'DELETE':
            handleDeleteRequest($clientObj);
            break;

        default:
            sendResponse(false, 'Nieobsługiwana metoda HTTP');
    }
} catch (Exception $e) {
    sendResponse(false, 'Błąd serwera: ' . $e->getMessage());
}

/**
 * Wysłanie odpowiedzi JSON
 */
function sendResponse($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Obsługa żądań GET
 */
function handleGetRequest($clientObj) {
    $action = $_GET['action'] ?? '';
    if ($action === 'list') {
        $clients = $clientObj->getAllClients();
        sendResponse(true, 'Lista klientów', $clients);
    } elseif ($action === 'get' && isset($_GET['id'])) {
        $clientData = $clientObj->getClientById((int)$_GET['id']);
        if ($clientData) {
            sendResponse(true, 'Dane klienta', $clientData);
        } else {
            sendResponse(false, 'Nie znaleziono klienta');
        }
    } elseif ($action === 'search' && isset($_GET['term'])) {
        $clients = $clientObj->searchClients($_GET['term']);
        sendResponse(true, 'Wyniki wyszukiwania', $clients);
    } else {
        sendResponse(false, 'Nieznana akcja');
    }
}

/**
 * Obsługa żądań POST - dodawanie nowego klienta
 */
function handlePostRequest($clientObj) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        sendResponse(false, 'Brak danych do przetworzenia.');
        return;
    }

    $errors = $clientObj->validateClientData($data);

    if (!empty($errors)) {
        sendResponse(false, 'Błędy walidacji', $errors);
        return;
    }

    $clientId = $clientObj->addClient($data);
    if ($clientId) {
        sendResponse(true, 'Klient został dodany', ['id' => $clientId]);
    } else {
        sendResponse(false, 'Błąd podczas dodawania klienta');
    }
}

/**
 * Obsługa żądań PUT - aktualizacja klienta
 */
function handlePutRequest($clientObj) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['client_id'])) {
        sendResponse(false, 'Nieprawidłowe ID klienta');
        return;
    }

    $errors = $clientObj->validateClientData($data);
    if (!empty($errors)) {
        sendResponse(false, 'Błędy walidacji', $errors);
        return;
    }
    
    $result = $clientObj->updateClient($data['client_id'], $data);
    if ($result) {
        sendResponse(true, 'Klient zaktualizowany');
    } else {
        sendResponse(false, 'Błąd aktualizacji klienta');
    }
}

/**
 * Obsługa żądań DELETE - usuwanie klienta
 */
function handleDeleteRequest($clientObj) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['client_id'])) {
        sendResponse(false, 'Nieprawidłowe ID klienta');
        return;
    }
    $result = $clientObj->deleteClient($data['client_id']);
    if ($result) {
        sendResponse(true, 'Klient usunięty');
    } else {
        sendResponse(false, 'Błąd usuwania klienta');
    }
}

?>