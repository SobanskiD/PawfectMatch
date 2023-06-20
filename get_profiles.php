<?php
// Połączenie z bazą danych
$host = 'localhost';
$db   = 'pawfect!';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Pobieranie profili użytkowników
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();

// Wyświetlanie profili użytkowników
foreach ($users as $user) {
    echo '<div class="profile">';
    echo '<h3>' . htmlspecialchars($user['name']) . '</h3>';
	 echo '<h3>' . htmlspecialchars($user['opis']) . '</h3>';
    echo '<p>Email: ' . htmlspecialchars($user['email']) . '</p>';
    echo '</div>';
}
?>
