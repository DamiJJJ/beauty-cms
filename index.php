<?php
require_once __DIR__ . '/classes/Client.php';
$clientObj = new Client();
$totalClients = $clientObj->countAllClients();
$recentClients = $clientObj->getRecentClients(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Beauty CMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">Beauty CMS</h1>
            <nav class="nav">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="pages/clients.php" class="nav-link">Clients</a>
                <a href="pages/appointments.php" class="nav-link">Appointments</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="page-header">
                <h2>Dashboard</h2>
            </div>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-value"><?= htmlspecialchars($totalClients) ?></div>
                    <div class="stat-label">Total Clients</div>
                    <a href="pages/clients.php" class="stat-link">Manage clients →</a>
                </div>
                <div class="stat-card">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Upcoming Appointments</div>
                    <a href="pages/appointments.php" class="stat-link">Schedule appointments →</a>
                </div>
            </div>

            <div class="card mt-2">
                <h3>Recently Added Clients</h3>
                <?php if (!empty($recentClients)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentClients as $client): ?>
                        <tr>
                            <td><?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?></td>
                            <td><?= date('Y-m-d', strtotime($client['created_at'])) ?></td>
                            <td><a href="pages/client_detail.php?id=<?= htmlspecialchars($client['id']) ?>">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p class="empty-state-small">No recently added clients.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="assets/js/main.js"></script>
</body>
</html>