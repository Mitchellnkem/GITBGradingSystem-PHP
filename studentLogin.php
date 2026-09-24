<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/includes/dbconnection.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errorMsg = '';
$matricNo = '';
$temporaryLoginEnabled = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricNo = trim((string) ($_POST['matricNo'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $token = (string) ($_POST['csrf_token'] ?? '');

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errorMsg = 'Your session expired. Please refresh and try again.';
    } elseif (($_POST['action'] ?? '') === 'temporary_login') {
        if (!$temporaryLoginEnabled) {
            http_response_code(403);
            $errorMsg = 'Temporary access is only available on this computer.';
        } else {
            $temporaryResult = mysqli_query($conn, 'SELECT matricNo, firstName, lastName FROM tblstudent ORDER BY Id ASC LIMIT 1');
            $student = $temporaryResult ? mysqli_fetch_assoc($temporaryResult) : null;
            if ($student) {
                session_regenerate_id(true);
                $_SESSION['matricNo'] = $student['matricNo'];
                $_SESSION['firstName'] = $student['firstName'];
                $_SESSION['lastName'] = $student['lastName'];
                $_SESSION['LAST'] = time();
                $_SESSION['temporary_login'] = true;
                header('Location: student/index.php');
                exit;
            }
            $errorMsg = 'No student account is available for temporary access.';
        }
    } elseif ($matricNo === '' || $password === '') {
        $errorMsg = 'Enter both your matric number and password.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT matricNo, firstName, lastName, password FROM tblstudent WHERE matricNo = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $matricNo);
        mysqli_stmt_execute($stmt);
        $student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        $validPassword = $student && (password_verify($password, $student['password']) || hash_equals((string) $student['password'], $password));

        if ($validPassword) {
            session_regenerate_id(true);
            $_SESSION['matricNo'] = $student['matricNo'];
            $_SESSION['firstName'] = $student['firstName'];
            $_SESSION['lastName'] = $student['lastName'];
            $_SESSION['LAST'] = time();
            unset($_SESSION['temporary_login']);
            header('Location: student/index.php');
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
    <meta name="theme-color" content="#071e35"><title>Student sign in | GITB Grading</title>
    <link rel="icon" href="assets/img/GITBRoundLogoblack.png"><link rel="stylesheet" href="assets/vendor/boxicons/css/boxicons.min.css"><link rel="stylesheet" href="assets/css/brand.css">
</head>
<body>
<main class="auth-page">
    <section class="auth-visual" style="--auth-image:url('img/office-buildings-with-modern-architecture.jpg')">
        <a class="brand" href="index.php"><img src="assets/img/GITBRoundLogowhite.png" alt=""><span>GITB Grading<small>Academic Portal</small></span></a>
        <div class="auth-message"><p class="eyebrow" style="color:#62dbb5">Student workspace</p><h1>Your academic journey, clearly in view.</h1><p>Access your course information, semester results, grading criteria, and complete academic record from one secure place.</p></div>
        <span class="auth-footnote">Genius IT Brainery · Unleash brilliance. Ignite impact.</span>
    </section>
    <section class="auth-panel"><div class="auth-box">
        <a class="auth-back" href="index.php"><i class="bx bx-left-arrow-alt"></i> Back to home</a>
        <p class="eyebrow">Welcome back</p><h2>Student sign in</h2><p class="auth-intro">Use the matric number and password issued by your institution.</p>
        <?php if ($errorMsg !== ''): ?><div class="alert-error" role="alert"><i class="bx bx-error-circle"></i> <?php echo htmlspecialchars($errorMsg); ?></div><?php endif; ?>
        <form method="post" autocomplete="on">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="field"><label for="matricNo">Matric number</label><div class="input-wrap"><i class="bx bx-id-card"></i><input id="matricNo" name="matricNo" type="text" value="<?php echo htmlspecialchars($matricNo); ?>" placeholder="e.g. SGS123" autocomplete="username" required autofocus></div></div>
            <div class="field"><label for="password">Password</label><div class="input-wrap"><i class="bx bx-lock-alt"></i><input id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required><button class="password-toggle" type="button" data-password-toggle="password" aria-label="Show password"><i class="bx bx-show"></i></button></div></div>
            <div class="form-meta"><span>Your details are securely processed.</span><a href="mailto:support@gitb.edu">Need help?</a></div>
            <button class="button auth-submit" type="submit">Sign in to portal <i class="bx bx-right-arrow-alt"></i></button>
        </form>
        <?php if ($temporaryLoginEnabled): ?>
            <div class="temporary-access"><span>Local development</span><p>Need a quick preview? Enter the portal using a temporary student session.</p><form method="post"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"><input type="hidden" name="action" value="temporary_login"><button class="temporary-button" type="submit"><i class="bx bx-time-five"></i> Temporary student login</button></form></div>
        <?php endif; ?>
        <p class="security-note"><i class="bx bx-shield-quarter"></i> Protected institutional access. Never share your password.</p>
    </div></section>
</main>
<script src="assets/js/brand.js"></script>
</body></html>
