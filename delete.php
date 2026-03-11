<?php
require 'config.php';

if (isset($_GET['delete_patient'])) {
    $id   = $_GET['delete_patient'];

    $stmt = $pdo->prepare("DELETE FROM patients WHERE patient_id = ?");
    $stmt->execute([$id]);
    header("Location: landing2.php?page=patients");
    exit;
}

if (isset($_GET['delete_consultation'])) {
    $id   = $_GET['delete_consultation'];
    $stmt = $pdo->prepare("DELETE FROM consultations WHERE consultation_id = ?");
    $stmt->execute([$id]);
    header("Location: landing2.php?page=consultations");
    exit;
}
?>