<?php

require_once __DIR__ . '/../classes/Client.php';
$clientObj = new Client();
$clients = $clientObj->getAllClients();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Beauty CMS</title>
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
                <h2>Appointments</h2>
                <button id="addAppointmentBtn" class="btn btn-primary">➕ Add Appointment</button>
            </div>
            
            <div id="appointmentsList">
                <div class="loading-overlay">
                    <div class="spinner"></div>
                    <p>Loading appointments...</p>
                </div>
            </div>
        </div>
    </main>

    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="BeautyApp.closeModal('appointmentModal')">&times;</span>
            <h3 id="modalTitle">Add New Appointment</h3>
            <form id="appointmentForm">
                <input type="hidden" id="appointmentId" name="id">
                <div class="form-group">
                    <label for="clientSelect">Select Client</label>
                    <select id="clientSelect" name="client_id" required>
                        <option value="">Select a client...</option>
                        <?php foreach ($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['id']) ?>">
                            <?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="treatmentType">Treatment Type</label>
                    <input type="text" id="treatmentType" name="treatment_type" required>
                </div>
                <div class="form-group">
                    <label for="appointmentDate">Date and Time</label>
                    <input type="datetime-local" id="appointmentDate" name="appointment_date" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="appointmentNotes">Notes</label>
                    <textarea id="appointmentNotes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Appointment</button>
            </form>
        </div>
    </div>
    
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="BeautyApp.closeModal('deleteModal')">&times;</span>
            <p>Are you sure you want to delete this appointment?</p>
            <button class="btn btn-danger" id="deleteConfirmBtn">Delete</button>
            <button class="btn btn-secondary" onclick="BeautyApp.closeModal('deleteModal')">Cancel</button>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/appointments.js"></script>
</body>
</html>