<?php
// pages/client_detail.php

require_once __DIR__ . '/../classes/Client.php';

$clientObj = new Client();
$clientId = (int)$_GET['id'] ?? 0;

if ($clientId > 0) {
    $client = $clientObj->getClientById($clientId);
}

if (!$client) {
    // Przekierowanie lub wyświetlenie błędu, jeśli klient nie istnieje
    header('Location: clients.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły klienta - Beauty CMS</title>
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
                <h2>Szczegóły klienta: <?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></h2>
                <a href="clients.php" class="btn btn-secondary">← Powrót do listy</a>
            </div>
            <div class="card client-details-card">
                <div class="details-section">
                    <p><strong>Imię:</strong> <?= htmlspecialchars($client['first_name']); ?></p>
                    <p><strong>Nazwisko:</strong> <?= htmlspecialchars($client['last_name']); ?></p>
                    <p><strong>Telefon:</strong> <?= htmlspecialchars($client['phone'] ?? 'Brak'); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($client['email'] ?? 'Brak'); ?></p>
                    <p><strong>Data urodzenia:</strong> <?= htmlspecialchars($client['birth_date'] ?? 'Brak'); ?></p>
                </div>
                
                <?php if (!empty($client['notes'])): ?>
                <div class="notes-section">
                    <h3>Notatki</h3>
                    <div class="notes-content"><?= nl2br(htmlspecialchars($client['notes'])); ?></div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </main>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>