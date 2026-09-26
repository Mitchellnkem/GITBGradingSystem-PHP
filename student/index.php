<?php
require_once '../includes/dbconnection.php';
require_once '../includes/session.php';

$firstName = $_SESSION['firstName'] ?? 'Student';
$profileStmt = mysqli_prepare(
    $conn,
    'SELECT student.departmentId, student.levelId, department.departmentName,
            faculty.facultyName, level.levelName
     FROM tblstudent AS student
     LEFT JOIN tbldepartment AS department ON department.Id = student.departmentId
     LEFT JOIN tblfaculty AS faculty ON faculty.Id = student.facultyId
     LEFT JOIN tbllevel AS level ON level.Id = student.levelId
     WHERE student.matricNo = ?
     LIMIT 1'
);
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
$departmentName = $profileRow['departmentName'] ?? 'Not assigned';
$facultyName = $profileRow['facultyName'] ?? 'Not assigned';
$levelName = $profileRow['levelName'] ?? 'Not assigned';
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
    <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="stylesheet" href="../assets/css/dashboard-modern.css">
</head>
<body>
<?php $page = 'dashboard'; include 'includes/leftMenu.php'; ?>
<div id="right-panel" class="right-panel">
    <?php include 'includes/header.php'; ?>
    <div class="content"><div class="animated fadeIn">
        <section class="portal-welcome">
            <div class="portal-welcome-copy"><span class="portal-kicker"><i class="fa fa-circle"></i> Academic overview</span><h1>Good day, <?php echo htmlspecialchars($firstName); ?>.</h1><p>Your courses, results, and academic details are all in one place.</p></div>
            <div class="portal-welcome-meta"><div><span>Department</span><strong><?php echo htmlspecialchars($departmentName); ?></strong></div><div><span>Faculty</span><strong><?php echo htmlspecialchars($facultyName); ?></strong></div></div>
        </section>

        <section class="portal-metrics" aria-label="Academic summary">
            <a class="portal-metric" href="studentCourses.php"><span class="portal-metric-icon"><i class="fa fa-book"></i></span><span><small>Available courses</small><strong><?php echo (int) $countAllStudentCourses; ?></strong><em>View course list <i class="fa fa-arrow-right"></i></em></span></a>
            <a class="portal-metric" href="studentResult.php"><span class="portal-metric-icon"><i class="fa fa-line-chart"></i></span><span><small>Published results</small><strong><?php echo (int) $countAllStudResult; ?></strong><em>Review performance <i class="fa fa-arrow-right"></i></em></span></a>
            <div class="portal-metric"><span class="portal-metric-icon"><i class="fa fa-graduation-cap"></i></span><span><small>Current level</small><strong class="portal-metric-text"><?php echo htmlspecialchars($levelName); ?></strong><em>Academic placement</em></span></div>
            <div class="portal-metric"><span class="portal-metric-icon"><i class="fa fa-calendar-check-o"></i></span><span><small>Active session</small><strong class="portal-metric-text"><?php echo htmlspecialchars($activeSession); ?></strong><em>Current academic year</em></span></div>
        </section>

        <section class="portal-card portal-quick-actions"><div class="portal-card-title"><div><span class="portal-section-label">Shortcuts</span><h2>Continue your academic journey</h2><p>Jump straight to the things you use most.</p></div></div><div class="quick-grid">
            <a class="quick-link" href="studentCourses.php"><i class="fa fa-book"></i><span><strong>View my courses</strong><small>Browse course details and units</small></span></a>
            <a class="quick-link" href="studentResult.php"><i class="fa fa-line-chart"></i><span><strong>Check results</strong><small>Review semester performance</small></span></a>
            <a class="quick-link" href="gradingCriteria.php"><i class="fa fa-graduation-cap"></i><span><strong>Grading guide</strong><small>Understand scores and grade points</small></span></a>
        </div></section>
    </div></div>
    <div class="clearfix"></div><?php include 'includes/footer.php'; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script><script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script><script src="../assets/js/main.js"></script>
</body></html>
