<?php
require 'config.php';

$stmt = $pdo->prepare("
    SELECT
        c.consultation_id,
        c.patient_id,
        c.doctor_name,
        c.consultation_date,
        c.diagnosis,
        c.treatment,
        p.full_name,
        p.age,
        p.gender,
        p.contact_number
    FROM consultations c
    INNER JOIN patients p ON c.patient_id = p.patient_id
    ORDER BY c.consultation_id DESC
");
$stmt->execute();
$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>