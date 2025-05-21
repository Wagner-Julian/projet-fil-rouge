<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';


$stmt = $pdo->query("SELECT *, DATE_FORMAT(date_cours, '%d/%m/%Y') AS date_normal FROM cours 
JOIN type ON cours.id_type = type.id_type 
ORDER BY date_cours ASC");
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../templates/cours.html.php';
