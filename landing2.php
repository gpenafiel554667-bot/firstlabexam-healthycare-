<?php
require 'insert.php';
require 'update.php';
require 'delete.php';
require 'select.php';
require 'join.php';

$page   = $_GET['page']   ?? 'patients';
$action = $_GET['action'] ?? '';

$editPatient      = null;
$editConsultation = null;

if ($action === 'edit_patient' && isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $editPatient = $stmt->fetch(PDO::FETCH_ASSOC);
}
if ($action === 'edit_consultation' && isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM consultations WHERE consultation_id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $editConsultation = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthyCare Hospital</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
    background: #eef2f7;
    color: #1e293b;
    font-size: 14px;
    min-height: 100vh;
}

.site-header {
    background: #0f2744;
    height: 58px;
    padding: 0 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.header-brand {
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.header-back {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    text-decoration: none;
}
.header-back:hover { color: #fff; }

.site-nav {
    background: #fff;
    padding: 0 36px;
    display: flex;
    border-bottom: 1px solid #e2e8f0;
}
.site-nav a {
    display: inline-block;
    padding: 14px 20px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    transition: color 0.15s;
}
.site-nav a.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
    font-weight: 600;
}
.site-nav a:hover { color: #1e293b; }

.main-content {
    max-width: 1000px;
    margin: 30px auto;
    padding: 0 24px;
}

.card {
    background: #fff;
    border-radius: 12px;
    padding: 28px 30px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
}
.card-title {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 20px;
}

.form-inline {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
.form-inline input,
.form-inline select {
    flex: 1;
    min-width: 120px;
    padding: 9px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    transition: border 0.15s, box-shadow 0.15s;
}
.form-inline input:focus,
.form-inline select:focus {
    border-color: #2563eb;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
}
.form-inline input::placeholder { color: #94a3b8; }

.form-stacked { display: flex; flex-direction: column; gap: 14px; max-width: 480px; }
.form-grid    { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; max-width: 680px; }
.form-group   { display: flex; flex-direction: column; gap: 5px; }
.form-group label {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
.form-stacked input, .form-stacked select,
.form-grid input,    .form-grid select {
    padding: 9px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    color: #1e293b;
    background: #f8fafc;
    outline: none;
    transition: border 0.15s, box-shadow 0.15s;
}
.form-stacked input:focus, .form-stacked select:focus,
.form-grid input:focus,    .form-grid select:focus {
    border-color: #2563eb;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
}

.btn {
    padding: 9px 22px;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    white-space: nowrap;
    transition: background 0.15s;
}
.btn-primary       { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-warning       { background: #f59e0b; color: #fff; }
.btn-warning:hover { background: #d97706; }
.btn-secondary     { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.btn-secondary:hover { background: #e2e8f0; }


.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead tr { border-bottom: 2px solid #f1f5f9; }
th {
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
td {
    padding: 13px 14px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    vertical-align: middle;
}
tr:last-child td  { border-bottom: none; }
tbody tr:hover td { background: #f8fafc; }


.badge {
    display: inline-block;
    padding: 3px 11px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.badge-blue  { background: #eff6ff; color: #3b82f6; }
.badge-pink  { background: #fdf2f8; color: #db2777; }
.badge-green { background: #f0fdf4; color: #16a34a; }


.action-links { display: flex; gap: 10px; }
.link-edit {
    font-size: 13px; font-weight: 600; color: #2563eb;
    text-decoration: none; padding: 4px 12px;
    background: #eff6ff; border-radius: 6px;
    transition: background 0.15s;
}
.link-edit:hover { background: #dbeafe; }
.link-delete {
    font-size: 13px; font-weight: 600; color: #ef4444;
    text-decoration: none; padding: 4px 12px;
    background: #fef2f2; border-radius: 6px;
    transition: background 0.15s;
}
.link-delete:hover { background: #fee2e2; }


.patient-name { font-weight: 600; color: #1e293b; }
.muted        { color: #94a3b8; font-size: 13px; }
.rec-id       { color: #94a3b8; font-weight: 600; font-size: 13px; }
.empty-row td { text-align: center; color: #94a3b8; padding: 40px; }
.edit-actions { display: flex; gap: 10px; margin-top: 20px; }
.span-2       { grid-column: span 2; }
</style>
</head>
<body>


<div class="site-header">
    <div class="header-brand">HealthyCare Hospital</div>
    <a href="landing.php" class="header-back">← Home</a>
</div>


<nav class="site-nav">
    <a href="landing2.php?page=patients"      class="<?= $page === 'patients'      ? 'active' : '' ?>">Patients</a>
    <a href="landing2.php?page=consultations" class="<?= $page === 'consultations' ? 'active' : '' ?>">Consultations</a>
</nav>

<div class="main-content">

<?php 
    
if ($page === 'patients'): ?>

    <div class="card">
        <div class="card-title"><?= $editPatient ? 'Edit Patient' : 'Add New Patient' ?></div>

        <?php if ($editPatient): ?>
            <form method="POST">
                <input type="hidden" name="patient_id" value="<?= $editPatient['patient_id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($editPatient['full_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" value="<?= $editPatient['age'] ?>" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male"   <?= $editPatient['gender'] === 'Male'   ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $editPatient['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other"  <?= $editPatient['gender'] === 'Other'  ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact_number" value="<?= htmlspecialchars($editPatient['contact_number']) ?>">
                    </div>
                </div>
                <div class="edit-actions">
                    <button type="submit" name="update_patient" class="btn btn-warning">Save Changes</button>
                    <a href="landing2.php?page=patients" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

        <?php else: ?>
            <form method="POST">
                <div class="form-inline">
                    <input type="text"   name="full_name"      placeholder="Full Name"      required>
                    <input type="number" name="age"            placeholder="Age"  min="0"   required>
                    <select name="gender" required>
                        <option value="">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text"   name="contact_number" placeholder="Contact Number">
                    <button type="submit" name="add_patient" class="btn btn-primary">Add Patient</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($patients)): ?>
                        <tr class="empty-row"><td colspan="6">No patients yet. Add your first patient above.</td></tr>
                    <?php else: ?>
                        <?php foreach ($patients as $p): ?>
                        <tr>
                            <td class="rec-id">#<?= $p['patient_id'] ?></td>
                            <td class="patient-name"><?= htmlspecialchars($p['full_name']) ?></td>
                            <td><?= $p['age'] ?></td>
                            <td>
                                <?php
                                $gc = $p['gender'] === 'Female' ? 'badge-pink' : ($p['gender'] === 'Male' ? 'badge-blue' : 'badge-green');
                                ?>
                                <span class="badge <?= $gc ?>"><?= $p['gender'] ?></span>
                            </td>
                            <td class="muted"><?= $p['contact_number'] ?: '—' ?></td>
                            <td>
                                <div class="action-links">
                                    <a href="landing2.php?page=patients&action=edit_patient&edit_id=<?= $p['patient_id'] ?>" class="link-edit">Edit</a>
                                    <a href="delete.php?delete_patient=<?= $p['patient_id'] ?>" class="link-delete"
                                       onclick="return confirm('Delete this patient? All their consultations will also be deleted.')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php 
elseif ($page === 'consultations'): ?>

    <div class="card">
        <div class="card-title"><?= $editConsultation ? 'Edit Consultation #' . $editConsultation['consultation_id'] : 'Add New Consultation' ?></div>

        <?php if ($editConsultation): ?>
            <form method="POST">
                <input type="hidden" name="consultation_id" value="<?= $editConsultation['consultation_id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Patient</label>
                        <select name="patient_id" required>
                            <option value="">-- Select Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['patient_id'] ?>" <?= $editConsultation['patient_id'] == $p['patient_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doctor Name</label>
                        <input type="text" name="doctor_name" value="<?= htmlspecialchars($editConsultation['doctor_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Consultation Date</label>
                        <input type="date" name="consultation_date" value="<?= $editConsultation['consultation_date'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Diagnosis</label>
                        <input type="text" name="diagnosis" value="<?= htmlspecialchars($editConsultation['diagnosis']) ?>">
                    </div>
                    <div class="form-group span-2">
                        <label>Treatment</label>
                        <input type="text" name="treatment" value="<?= htmlspecialchars($editConsultation['treatment']) ?>">
                    </div>
                </div>
                <div class="edit-actions">
                    <button type="submit" name="update_consultation" class="btn btn-warning">Save Changes</button>
                    <a href="landing2.php?page=consultations" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

        <?php else: ?>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Patient <span style="color:#ef4444">*</span></label>
                        <select name="patient_id" required>
                            <option value="">-- Select Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doctor Name <span style="color:#ef4444">*</span></label>
                        <input type="text" name="doctor_name" placeholder="e.g. Dr. Santos" required>
                    </div>
                    <div class="form-group">
                        <label>Consultation Date <span style="color:#ef4444">*</span></label>
                        <input type="date" name="consultation_date" required>
                    </div>
                    <div class="form-group">
                        <label>Diagnosis</label>
                        <input type="text" name="diagnosis" placeholder="e.g. Hypertension">
                    </div>
                    <div class="form-group span-2">
                        <label>Treatment</label>
                        <input type="text" name="treatment" placeholder="e.g. Prescribed medication, rest">
                    </div>
                </div>
                <div class="edit-actions">
                    <button type="submit" name="add_consultation" class="btn btn-primary">Add Consultation</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($consultations)): ?>
                        <tr class="empty-row"><td colspan="7">No consultations yet. Add one above.</td></tr>
                    <?php else: ?>
                        <?php foreach ($consultations as $c): ?>
                        <tr>
                            <td class="rec-id">#<?= $c['consultation_id'] ?></td>
                            <td class="patient-name"><?= htmlspecialchars($c['full_name']) ?></td>
                            <td><?= htmlspecialchars($c['doctor_name']) ?></td>
                            <td class="muted"><?= date('M d, Y', strtotime($c['consultation_date'])) ?></td>
                            <td><?= htmlspecialchars($c['diagnosis'] ?: '—') ?></td>
                            <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">
                                <?= htmlspecialchars($c['treatment'] ?: '—') ?>
                            </td>
                            <td>
                                <div class="action-links">
                                    <a href="landing2.php?page=consultations&action=edit_consultation&edit_id=<?= $c['consultation_id'] ?>" class="link-edit">Edit</a>
                                    <a href="delete.php?delete_consultation=<?= $c['consultation_id'] ?>" class="link-delete"
                                       onclick="return confirm('Delete this consultation?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>
</div>
</body>
</html>