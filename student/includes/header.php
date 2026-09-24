<header id="header" class="header">
    <div class="top-left"><div class="navbar-header"><a class="navbar-brand" href="index.php">Student workspace</a><a id="menuToggle" class="menutoggle" aria-label="Toggle navigation"><i class="fa fa-bars"></i></a></div></div>
    <div class="top-right"><div class="header-menu">
        <div class="user-area dropdown float-right">
            <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="user-avatar rounded-circle" src="../assets/img/user2.png" alt="<?php echo htmlspecialchars($_SESSION['firstName'] ?? 'Student'); ?>"></a>
            <div class="user-menu dropdown-menu"><div class="nav-link"><strong><?php echo htmlspecialchars(($_SESSION['firstName'] ?? '') . ' ' . ($_SESSION['lastName'] ?? '')); ?></strong></div><a class="nav-link" href="updateProfile.php"><i class="fa fa-user"></i> My profile</a><a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Sign out</a></div>
        </div>
    </div></div>
</header>
