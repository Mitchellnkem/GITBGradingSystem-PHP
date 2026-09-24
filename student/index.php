<?php
require_once '../includes/dbconnection.php';
require_once '../includes/session.php';

$firstName = $_SESSION['firstName'] ?? 'Student';
$profileStmt = mysqli_prepare($conn, 'SELECT departmentId, levelId FROM tblstudent WHERE matricNo = ? LIMIT 1');
mysqli_stmt_bind_param($profileStmt, 's', $matricNo); mysqli_stmt_execute($profileStmt);
$profileRow = mysqli_fetch_assoc(mysqli_stmt_get_result($profileStmt)) ?: [];
$studentDepartmentId = (int) ($profileRow['departmentId'] ?? 0);
$studentLevelId = (int) ($profileRow['levelId'] ?? 0);

$courseCountStmt = mysqli_prepare($conn, 'SELECT COUNT(*) AS total FROM tblcourse WHERE departmentId = ? AND levelId = ?');
mysqli_stmt_bind_param($courseCountStmt, 'ii', $studentDepartmentId, $studentLevelId); mysqli_stmt_execute($courseCountStmt);
$countAllStudentCourses = (int) ((mysqli_fetch_assoc(mysqli_stmt_get_result($courseCountStmt)))['total'] ?? 0);
$resultCountStmt = mysqli_prepare($conn, 'SELECT COUNT(*) AS total FROM tblfinalresult WHERE matricNo = ?');
mysqli_stmt_bind_param($resultCountStmt, 's', $matricNo); mysqli_stmt_execute($resultCountStmt);
$countAllStudResult = (int) ((mysqli_fetch_assoc(mysqli_stmt_get_result($resultCountStmt)))['total'] ?? 0);
$facultyCountResult = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tblfaculty');
$countFaculty = (int) ((mysqli_fetch_assoc($facultyCountResult))['total'] ?? 0);
$departmentCountResult = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM tbldepartment');
$countDepartment = (int) ((mysqli_fetch_assoc($departmentCountResult))['total'] ?? 0);
$activeSession = 'Not set';
$sessionQuery = mysqli_query($conn, 'SELECT sessionName FROM tblsession WHERE isActive = 1 LIMIT 1');
if ($sessionQuery && ($sessionRow = mysqli_fetch_assoc($sessionQuery))) {
    $activeSession = $sessionRow['sessionName'];
}
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include 'includes/title.php'; ?>
    <meta name="description" content="GITB student academic dashboard">
    <link rel="shortcut icon" href="../assets/img/student-grade.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css"><link rel="stylesheet" href="../assets/css/style2.css">
</head>
<body>
<?php $page = 'dashboard'; include 'includes/leftMenu.php'; ?>
<div id="right-panel" class="right-panel">
    <?php include 'includes/header.php'; ?>
    <div class="content"><div class="animated fadeIn">
        <div class="row"><div class="col-12"><div class="card"><div class="card-body portal-welcome"><div class="portal-welcome-wrap">
            <div><p class="eyebrow">Academic overview</p><h1>Good day, <?php echo htmlspecialchars($firstName); ?>.</h1><p>Here is a quick view of your academic workspace and records.</p></div>
            <span class="portal-date"><i class="fa fa-calendar"></i>&nbsp; Active session: <?php echo htmlspecialchars($activeSession); ?></span>
        </div></div></div></div></div>

        <div class="row">
            <div class="col-sm-6 col-lg-3"><div class="card text-white bg-flat-color-3"><div class="card-body"><div class="card-left pt-1 float-left"><h3 class="mb-0 fw-r"><?php echo (int) $countAllStudentCourses; ?></h3><p class="text-light mt-1 m-0">Available courses</p></div><div class="card-right float-right text-right"><i class="icon fade-5 icon-lg pe-7s-notebook"></i></div></div></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card text-white bg-success"><div class="card-body"><div class="card-left pt-1 float-left"><h3 class="mb-0 fw-r"><?php echo (int) $countAllStudResult; ?></h3><p class="text-light mt-1 m-0">Published results</p></div><div class="card-right float-right text-right"><i class="icon fade-5 icon-lg pe-7s-copy-file"></i></div></div></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card text-white bg-secondary"><div class="card-body"><div class="card-left pt-1 float-left"><h3 class="mb-0 fw-r"><?php echo (int) $countFaculty; ?></h3><p class="text-light mt-1 m-0">Faculties</p></div><div class="card-right float-right text-right"><i class="icon fade-5 icon-lg pe-7s-study"></i></div></div></div></div>
            <div class="col-sm-6 col-lg-3"><div class="card text-white bg-flat-color-2"><div class="card-body"><div class="card-left pt-1 float-left"><h3 class="mb-0 fw-r"><?php echo (int) $countDepartment; ?></h3><p class="text-light mt-1 m-0">Departments</p></div><div class="card-right float-right text-right"><i class="icon fade-5 icon-lg pe-7s-network"></i></div></div></div></div>
        </div>

        <div class="row"><div class="col-12"><div class="card"><div class="card-body"><div class="mb-4"><h4 class="mb-1"><strong>Quick actions</strong></h4><p class="text-muted mb-0">Jump straight to the things you use most.</p></div><div class="quick-grid">
            <a class="quick-link" href="studentCourses.php"><i class="fa fa-book"></i><span><strong>View my courses</strong><small>Browse course details and units</small></span></a>
            <a class="quick-link" href="studentResult.php"><i class="fa fa-line-chart"></i><span><strong>Check results</strong><small>Review semester performance</small></span></a>
            <a class="quick-link" href="gradingCriteria.php"><i class="fa fa-graduation-cap"></i><span><strong>Grading guide</strong><small>Understand scores and grade points</small></span></a>
        </div></div></div></div></div>
    </div></div>
    <div class="clearfix"></div><?php include 'includes/footer.php'; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script><script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script><script src="../assets/js/main.js"></script>
</body></html>
