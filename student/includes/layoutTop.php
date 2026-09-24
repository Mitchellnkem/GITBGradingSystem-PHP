<?php
$page = $page ?? '';
$pageTitle = $pageTitle ?? 'Student portal';
$pageEyebrow = $pageEyebrow ?? 'Student workspace';
$pageDescription = $pageDescription ?? '';
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?> | GITB Grading</title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <link rel="shortcut icon" href="../assets/img/student-grade.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css"><link rel="stylesheet" href="../assets/css/style2.css">
</head>
<body>
<?php include __DIR__ . '/leftMenu.php'; ?>
<div id="right-panel" class="right-panel">
    <?php include __DIR__ . '/header.php'; ?>
    <main class="content"><div class="animated fadeIn">
        <?php if (!empty($_SESSION['temporary_login'])): ?><div class="portal-notice"><i class="fa fa-clock-o"></i><span><strong>Temporary preview session</strong>This session is for local testing. Account changes are disabled.</span></div><?php endif; ?>
        <header class="portal-page-heading"><div><p><?php echo htmlspecialchars($pageEyebrow); ?></p><h1><?php echo htmlspecialchars($pageTitle); ?></h1><span><?php echo htmlspecialchars($pageDescription); ?></span></div><a href="index.php"><i class="fa fa-arrow-left"></i> Dashboard</a></header>
