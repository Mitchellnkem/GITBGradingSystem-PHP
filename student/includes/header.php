<?php
$headerFirstName = $_SESSION['firstName'] ?? 'Student';
$headerLastName = $_SESSION['lastName'] ?? '';
$headerInitials = strtoupper(substr($headerFirstName, 0, 1) . substr($headerLastName, 0, 1));
?>
<header id="header" class="header">
    <div class="top-left"><div class="navbar-header"><a id="menuToggle" class="menutoggle" href="#" aria-label="Toggle navigation"><i class="fa fa-bars"></i></a><a class="navbar-brand" href="index.php"><span>Student portal</span><small>Academic workspace</small></a></div></div>
    <div class="top-right"><div class="header-menu">
        <div class="user-area dropdown float-right">
            <a href="#" class="dropdown-toggle active portal-user-trigger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="portal-user-avatar"><?php echo htmlspecialchars($headerInitials); ?></span><span class="portal-user-copy"><strong><?php echo htmlspecialchars($headerFirstName); ?></strong><small>Student account</small></span><i class="fa fa-angle-down"></i></a>
            <div class="user-menu dropdown-menu"><div class="portal-menu-identity"><span class="portal-user-avatar"><?php echo htmlspecialchars($headerInitials); ?></span><div><strong><?php echo htmlspecialchars(trim($headerFirstName . ' ' . $headerLastName)); ?></strong><small>Signed in</small></div></div><div class="dropdown-divider"></div><a class="nav-link" href="updateProfile.php"><i class="fa fa-user"></i> My profile</a><a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Sign out</a></div>
        </div>
    </div></div>
</header>
