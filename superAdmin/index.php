<?php
require_once '../includes/dbconnection.php';
require_once '../includes/session.php';
if (!isset($_SESSION['staffId'])) { header('Location: ../adminLogin.php'); exit; }

function tableCount(mysqli $conn, string $table): int {
    $allowed = ['tblstudent','tblstaff','tblcourse','tbldepartment','tblfaculty','tblfinalresult'];
    if (!in_array($table, $allowed, true)) return 0;
    $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM {$table}");
    $row = $result ? mysqli_fetch_assoc($result) : null;
    return (int) ($row['total'] ?? 0);
}
$students = tableCount($conn, 'tblstudent');
$staff = tableCount($conn, 'tblstaff');
$courses = tableCount($conn, 'tblcourse');
$results = tableCount($conn, 'tblfinalresult');
$activeSession = 'Not configured';
$sessionResult = mysqli_query($conn, 'SELECT sessionName FROM tblsession WHERE isActive = 1 LIMIT 1');
if ($sessionResult && ($sessionRow = mysqli_fetch_assoc($sessionResult))) $activeSession = $sessionRow['sessionName'];
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Administration | GITB Grading</title>
<link rel="icon" href="../assets/img/GITBRoundLogoblack.png"><link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css"><link rel="stylesheet" href="../assets/vendor/boxicons/css/boxicons.min.css"><link rel="stylesheet" href="../assets/css/brand.css">
<style>
.admin-shell{min-height:100vh;background:#f4f7fa}.admin-nav{height:78px;background:#071e35;color:#fff}.admin-nav .shell{height:100%;display:flex;align-items:center;justify-content:space-between}.admin-user{display:flex;align-items:center;gap:14px}.admin-user small{display:block;color:#86a0b7}.admin-layout{padding:42px 0 80px}.admin-hero{padding:34px;border-radius:24px;color:#fff;background:linear-gradient(120deg,#0c2d4d,#087254);box-shadow:0 20px 50px rgba(8,51,65,.18)}.admin-hero h1{margin:7px 0;font-size:2.3rem;letter-spacing:-.04em}.admin-hero p{margin:0;color:rgba(255,255,255,.72)}.admin-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin:25px 0}.admin-stat{padding:25px;border:1px solid #e2e8f0;border-radius:18px;background:#fff}.admin-stat i{font-size:1.5rem;color:#12a87a}.admin-stat strong{display:block;margin:18px 0 2px;font-size:2rem}.admin-stat span{color:#66758a;font-size:.82rem}.admin-lower{display:grid;grid-template-columns:1.4fr .6fr;gap:20px}.admin-card{padding:28px;border:1px solid #e2e8f0;border-radius:20px;background:#fff}.admin-card h2{font-size:1.2rem}.admin-actions{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:22px}.admin-action{padding:18px;border-radius:14px;background:#f1f6f8}.admin-action strong{display:block}.admin-action small{color:#66758a}.session-badge{display:inline-block;margin-top:20px;padding:10px 14px;border-radius:10px;color:#087254;background:#e3f7f0;font-weight:800}.admin-signout{padding:10px 14px;border:1px solid rgba(255,255,255,.22);border-radius:10px;color:#fff}@media(max-width:800px){.admin-grid{grid-template-columns:1fr 1fr}.admin-lower{grid-template-columns:1fr}}@media(max-width:520px){.admin-grid,.admin-actions{grid-template-columns:1fr}.admin-user span{display:none}}
</style></head><body class="admin-shell">
<header class="admin-nav"><div class="shell"><a class="brand" href="index.php"><img src="../assets/img/GITBRoundLogowhite.png" alt=""><span>GITB Grading<small>Administration</small></span></a><div class="admin-user"><span><strong><?php echo htmlspecialchars($_SESSION['firstName'] ?? 'Administrator'); ?></strong><small>Authorised staff</small></span><a class="admin-signout" href="logout.php">Sign out</a></div></div></header>
<main class="shell admin-layout">
<section class="admin-hero"><span class="eyebrow" style="color:#79e2c2">Institution overview</span><h1>Welcome back, <?php echo htmlspecialchars($_SESSION['firstName'] ?? 'Administrator'); ?>.</h1><p>A clear view of the academic records currently held in the GITB grading system.</p></section>
<section class="admin-grid" aria-label="Record totals"><div class="admin-stat"><i class="bx bx-user"></i><strong><?php echo $students; ?></strong><span>Student records</span></div><div class="admin-stat"><i class="bx bx-group"></i><strong><?php echo $staff; ?></strong><span>Staff records</span></div><div class="admin-stat"><i class="bx bx-book-open"></i><strong><?php echo $courses; ?></strong><span>Courses</span></div><div class="admin-stat"><i class="bx bx-file"></i><strong><?php echo $results; ?></strong><span>Published results</span></div></section>
<section class="admin-lower"><div class="admin-card"><p class="eyebrow">Workspace</p><h2>Academic management at a glance</h2><p style="color:#66758a">This installation contains the core grading data and student experience. The overview below confirms the main areas available in the imported database.</p><div class="admin-actions"><div class="admin-action"><strong>Student records</strong><small>Profiles and programme assignments</small></div><div class="admin-action"><strong>Course catalogue</strong><small>Units, levels and semesters</small></div><div class="admin-action"><strong>Academic results</strong><small>Scores, grades and GPA records</small></div><div class="admin-action"><strong>Institution structure</strong><small>Faculties and departments</small></div></div></div><aside class="admin-card"><p class="eyebrow">Current session</p><h2>Academic year</h2><span class="session-badge"><i class="bx bx-calendar-check"></i> <?php echo htmlspecialchars($activeSession); ?></span><p style="margin-top:22px;color:#66758a;font-size:.85rem">All dashboard totals are read live from the database.</p></aside></section>
</main></body></html>
