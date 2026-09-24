<?php
$stmt = mysqli_prepare($conn, 'SELECT firstName, lastName, departmentId, facultyId, levelId FROM tblstudent WHERE matricNo = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $matricNo);
mysqli_stmt_execute($stmt);
$studentProfile = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: [];
$fullName = trim(($studentProfile['firstName'] ?? 'Student') . ' ' . ($studentProfile['lastName'] ?? ''));
$departmentId = $studentProfile['departmentId'] ?? 0;
$facultyId = $studentProfile['facultyId'] ?? 0;
$levelId = $studentProfile['levelId'] ?? 0;
$page = $page ?? '';
?>
<aside id="left-panel" class="left-panel">
    <a class="portal-sidebar-brand" href="index.php"><img src="../assets/img/GITBRoundLogowhite.png" alt=""><span>GITB Grading<small>Student portal</small></span></a>
    <div class="portal-student"><span>Signed in as</span><strong><?php echo htmlspecialchars($fullName); ?></strong></div>
    <nav class="navbar navbar-expand-sm navbar-default" aria-label="Student navigation">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="menu-title">Overview</li>
                <li class="<?php echo $page === 'dashboard' ? 'active' : ''; ?>"><a href="index.php"><i class="menu-icon fa fa-dashboard"></i>Dashboard</a></li>
                <li class="menu-title">Academics</li>
                <li class="<?php echo $page === 'courses' ? 'active' : ''; ?>"><a href="studentCourses.php"><i class="menu-icon fa fa-book"></i>My courses</a></li>
                <li class="menu-item-has-children dropdown <?php echo $page === 'result' ? 'active' : ''; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="menu-icon fa fa-file-text"></i>Results</a>
                    <ul class="sub-menu children dropdown-menu"><li><a href="studentResult.php">Semester results</a></li><li><a href="viewFinalResult.php">Final result</a></li><li><a href="gradingCriteria.php">Grading guide</a></li></ul>
                </li>
                <li class="menu-title">Account</li>
                <li class="<?php echo $page === 'profile' ? 'active' : ''; ?>"><a href="updateProfile.php"><i class="menu-icon fa fa-user-circle"></i>My profile</a></li>
                <li><a href="changePassword.php"><i class="menu-icon fa fa-key"></i>Password</a></li>
                <li><a href="logout.php"><i class="menu-icon fa fa-power-off"></i>Sign out</a></li>
            </ul>
        </div>
    </nav>
</aside>
