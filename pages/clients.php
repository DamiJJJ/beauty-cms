<?php
require_once __DIR__ . '/../classes/Client.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klienci - Beauty CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">Beauty CMS</h1>
            <nav class="nav">
                <a href="../index.php" class="nav-link">Dashboard</a>
                <a href="clients.php" class="nav-link">Klienci</a>
                <a href="appointments.php" class="nav-link">Wizyty</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="page-header">
                <h2>Klienci</h2>
                <button id="addClientBtn" class="btn btn-primary">➕ Dodaj klienta</button>
            </div>
        
            <div class="clients-grid" id="clientsList">
                <div class="loading-overlay">
                    <div class="spinner"></div>
                    <p>Ładowanie klientów...</p>
                </div>
            </div>
        </div>
    </main>

    <div id="clientModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="BeautyApp.closeModal('clientModal')">&times;</span>
            <h3 id="modalTitle">Dodaj nowego klienta</h3>
            <form id="clientForm">
                <input type="hidden" id="clientId" name="client_id">
                <div class="form-group">
                    <label for="firstName">Imię</label>
                    <input type="text" id="firstName" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Nazwisko</label>
                    <input type="text" id="lastName" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="birthDate">Data urodzenia</label>
                    <input type="date" id="birthDate" name="birth_date">
                </div>
                <div class="form-group">
                    <label for="notes">Notatki</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Zapisz klienta</button>
            </form>
        </div>
    </div>
    
    <div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="BeautyApp.closeModal('deleteModal')">&times;</span>
        <p>Czy na pewno chcesz usunąć klienta: <strong id="clientToDelete"></strong>?</p>
        <button class="btn btn-danger" id="deleteConfirmBtn">Usuń</button>
        <button class="btn btn-secondary" onclick="BeautyApp.closeModal('deleteModal')">Anuluj</button>
    </div>
</div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/clients.js"></script>
</body>
</html>