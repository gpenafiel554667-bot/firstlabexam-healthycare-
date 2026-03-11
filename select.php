<?php
require 'config.php';

$stmt = $pdo->prepare("SELECT * FROM patients ORDER BY patient_id DESC");
$stmt->execute();
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>