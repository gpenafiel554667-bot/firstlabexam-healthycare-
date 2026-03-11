<?php
require 'config.php';

if (isset($_POST['add_patient'])) {
    $full_name      = $_POST['full_name'];
    $age            = $_POST['age'];
    $gender         = $_POST['gender'];
    $contact_number = $_POST['contact_number'];

    $stmt = $pdo->prepare("INSERT INTO patients (full_name, age, gender, contact_number) VALUES (?, ?, ?, ?)");
    $stmt->execute([$full_name, $age, $gender, $contact_number]);

    header("Location: landing2.php?page=patients");
    exit;
}

if (isset($_POST['add_consultation'])) {
    $patient_id        = $_POST['patient_id'];
    $doctor_name       = $_POST['doctor_name'];
    $consultation_date = $_POST['consultation_date'];
    $diagnosis         = $_POST['diagnosis'];
    $treatment         = $_POST['treatment'];

    $stmt = $pdo->prepare("INSERT INTO consultations (patient_id, doctor_name, consultation_date, diagnosis, treatment) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$patient_id, $doctor_name, $consultation_date, $diagnosis, $treatment]);

    header("Location: landing2.php?page=consultations");
    exit;
}
?>