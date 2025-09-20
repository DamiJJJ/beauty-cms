<?php
require_once __DIR__ . '/../classes/Client.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - Beauty CMS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">Beauty CMS</h1>
            <nav class="nav">
                <a href="../index.php" class="nav-link">Dashboard</a>
                <a href="clients.php" class="nav-link">Clients</a>
                <a href="appointments.php" class="nav-link">Appointments</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="page-header">
                <h2>Clients</h2>
                <button id="addClientBtn" class="btn btn-primary">➕ Add Client</button>
            </div>
        
            <div class="clients-grid" id="clientsList">
                <div class="loading-overlay">
                    <div class="spinner"></div>
                    <p>Loading clients...</p>
                </div>
            </div>
        </div>
    </main>

    <div id="clientModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="BeautyApp.closeModal('clientModal')">&times;</span>
            <h3 id="modalTitle">Add New Client</h3>
            <form id="clientForm">
                <input type="hidden" id="clientId" name="client_id">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="birthDate">Date of Birth</label>
                    <input type="date" id="birthDate" name="birth_date">
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Save Client</button>
            </form>
        </div>
    </div>
    
    <div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="BeautyApp.closeModal('deleteModal')">&times;</span>
        <p>Are you sure you want to delete client: <strong id="clientToDelete"></strong>?</p>
        <button class="btn btn-danger" id="deleteConfirmBtn">Delete</button>
        <button class="btn btn-secondary" onclick="BeautyApp.closeModal('deleteModal')">Cancel</button>
    </div>
</div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/clients.js"></script>
</body>
</html>