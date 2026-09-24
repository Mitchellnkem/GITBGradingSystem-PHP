
<?php
require_once __DIR__ . '/dbconnection.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_SESSION['staffId']))
{
    $staffId = $_SESSION['staffId'];

}
else if(isset($_SESSION['matricNo'])){

   $matricNo = $_SESSION['matricNo'];
}

else{
  header('Location: ../index.php');
  exit;
}

$expiry = 1800 ;//session expiry required after 30 mins
if (isset($_SESSION['LAST']) && (time() - $_SESSION['LAST'] > $expiry)) {

    session_unset();
    session_destroy();
    header('Location: ../index.php?session=expired');
    exit;

}
$_SESSION['LAST'] = time();
    
?>
