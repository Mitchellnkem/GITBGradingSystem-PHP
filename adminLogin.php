<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/includes/dbconnection.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errorMsg = '';
$staffId = '';
$temporaryLoginEnabled = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffId = trim((string) ($_POST['staffId'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $token = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errorMsg = 'Your session expired. Please refresh and try again.';
    } elseif (($_POST['action'] ?? '') === 'temporary_login') {
        if (!$temporaryLoginEnabled) {
            http_response_code(403);
            $errorMsg = 'Temporary access is only available on this computer.';
        } else {
            $temporaryResult = mysqli_query($conn, 'SELECT staffId, emailAddress, firstName, lastName, adminTypeId FROM tbladmin ORDER BY adminTypeId ASC, Id ASC LIMIT 1');
            $admin = $temporaryResult ? mysqli_fetch_assoc($temporaryResult) : null;
            if ($admin) {
                session_regenerate_id(true);
                $_SESSION['staffId'] = $admin['staffId'];
                $_SESSION['emailAddress'] = $admin['emailAddress'];
                $_SESSION['firstName'] = $admin['firstName'];
                $_SESSION['lastName'] = $admin['lastName'];
                $_SESSION['adminTypeId'] = (int) $admin['adminTypeId'];
                $_SESSION['LAST'] = time();
                $_SESSION['temporary_login'] = true;
                header('Location: superAdmin/index.php');
                exit;
            }
            $errorMsg = 'No administrator account is available for temporary access.';
        }
    } elseif ($staffId === '' || $password === '') {
        $errorMsg = 'Enter both your staff ID and password.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT staffId, emailAddress, firstName, lastName, adminTypeId, password FROM tbladmin WHERE staffId = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $staffId);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        $storedPassword = $admin['password'] ?? '';
        $validPassword = $admin && (password_verify($password, $storedPassword) || hash_equals($storedPassword, md5($password)));

        if ($validPassword) {
            session_regenerate_id(true);
            $_SESSION['staffId'] = $admin['staffId'];
            $_SESSION['emailAddress'] = $admin['emailAddress'];
            $_SESSION['firstName'] = $admin['firstName'];
            $_SESSION['lastName'] = $admin['lastName'];
            $_SESSION['adminTypeId'] = (int) $admin['adminTypeId'];
            $_SESSION['LAST'] = time();
            unset($_SESSION['temporary_login']);
            header('Location: superAdmin/index.php');
            exit;
        }
        $errorMsg = 'We could not match those details. Check them and try again.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#071e35"><title>Administrator sign in | GITB Grading</title>
    <link rel="icon" href="assets/img/GITBRoundLogoblack.png"><link rel="stylesheet" href="assets/vendor/boxicons/css/boxicons.min.css"><link rel="stylesheet" href="assets/css/brand.css">
</head>
<body>
<main class="auth-page">
    <section class="auth-visual" style="--auth-image:url('img/front-desk-staff-managing-guest-checkin.jpg')">
        <a class="brand" href="index.php"><img src="assets/img/GITBRoundLogowhite.png" alt=""><span>GITB Grading<small>Academic Portal</small></span></a>
        <div class="auth-message"><p class="eyebrow" style="color:#62dbb5">Administration workspace</p><h1>Better oversight. Stronger outcomes.</h1><p>Manage academic information and monitor institutional performance through one focused, secure workspace.</p></div>
        <span class="auth-footnote">Authorised staff access only · Activity may be monitored.</span>
    </section>
    <section class="auth-panel"><div class="auth-box">
        <a class="auth-back" href="index.php"><i class="bx bx-left-arrow-alt"></i> Back to home</a>
        <p class="eyebrow">Secure access</p><h2>Administrator sign in</h2><p class="auth-intro">Enter your assigned staff credentials to continue.</p>
        <?php if ($errorMsg !== ''): ?><div class="alert-error" role="alert"><i class="bx bx-error-circle"></i> <?php echo htmlspecialchars($errorMsg); ?></div><?php endif; ?>
        <form method="post" autocomplete="on">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="field"><label for="staffId">Staff ID</label><div class="input-wrap"><i class="bx bx-id-card"></i><input id="staffId" name="staffId" type="text" value="<?php echo htmlspecialchars($staffId); ?>" placeholder="Enter your staff ID" autocomplete="username" required autofocus></div></div>
            <div class="field"><label for="password">Password</label><div class="input-wrap"><i class="bx bx-lock-alt"></i><input id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required><button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password"><i class="bx bx-show"></i></button></div></div>
            <div class="form-meta"><span>Restricted institutional area</span><a href="mailto:support@gitb.edu">Access issue?</a></div>
            <button class="button auth-submit" type="submit">Continue securely <i class="bx bx-right-arrow-alt"></i></button>
        </form>
        <?php if ($temporaryLoginEnabled): ?>
            <div class="temporary-access"><span>Local development</span><p>Need a quick preview? Enter the workspace using a temporary administrator session.</p><form method="post"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"><input type="hidden" name="action" value="temporary_login"><button class="temporary-button" type="submit"><i class="bx bx-time-five"></i> Temporary admin login</button></form></div>
        <?php endif; ?>
        <p class="security-note"><i class="bx bx-shield-quarter"></i> Your session automatically expires after 30 minutes of inactivity.</p>
    </div></section>
</main>
<script src="assets/js/brand.js"></script>
</body></html>
