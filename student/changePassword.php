<?php
require_once '../includes/dbconnection.php'; require_once '../includes/session.php';
if(empty($_SESSION['csrf_token']))$_SESSION['csrf_token']=bin2hex(random_bytes(32));
$message='';$messageType='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $token=(string)($_POST['csrf_token']??'');$current=(string)($_POST['currentPassword']??'');$new=(string)($_POST['newPassword']??'');$confirm=(string)($_POST['confirmPassword']??'');
    if(!hash_equals($_SESSION['csrf_token'],$token)){ $message='Your session expired. Refresh and try again.';$messageType='error'; }
    elseif(!empty($_SESSION['temporary_login'])){ $message='Password changes are disabled during a temporary preview session.';$messageType='error'; }
    elseif(strlen($new)<8){$message='Your new password must contain at least 8 characters.';$messageType='error';}
    elseif($new!==$confirm){$message='The new passwords do not match.';$messageType='error';}
    else{
        $stmt=mysqli_prepare($conn,'SELECT password FROM tblstudent WHERE matricNo=? LIMIT 1');mysqli_stmt_bind_param($stmt,'s',$matricNo);mysqli_stmt_execute($stmt);$row=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));$stored=(string)($row['password']??'');
        if(!$row||!(password_verify($current,$stored)||hash_equals($stored,$current))){$message='Your current password is incorrect.';$messageType='error';}
        else{$hash=password_hash($new,PASSWORD_DEFAULT);$stmt=mysqli_prepare($conn,'UPDATE tblstudent SET password=? WHERE matricNo=?');mysqli_stmt_bind_param($stmt,'ss',$hash,$matricNo);if(mysqli_stmt_execute($stmt)){$message='Your password was changed successfully.';$messageType='success';}else{$message='We could not change your password right now.';$messageType='error';}}
    }
}
$page='profile';$pageTitle='Password & security';$pageEyebrow='Account security';$pageDescription='Use a strong, unique password to keep your academic record protected.';require 'includes/layoutTop.php';
?>
<div class="profile-grid"><aside class="portal-card"><div style="display:grid;place-items:center;width:58px;height:58px;border-radius:17px;color:#fff;background:#071e35;font-size:25px"><i class="fa fa-shield"></i></div><h2 style="margin:22px 0 10px;font-size:20px">A safer password</h2><p style="color:#6b788b;line-height:1.7">Choose at least eight characters. A mix of words, numbers, and symbols makes your password more difficult to guess.</p><ul style="padding-left:18px;color:#526174;font-size:13px;line-height:2"><li>Never reuse your school password elsewhere</li><li>Do not share it with classmates or staff</li><li>Always sign out on shared computers</li></ul></aside>
<section class="portal-card"><div class="portal-card-title"><div><h2>Change password</h2><p>You will use the new password the next time you sign in.</p></div></div><?php if($message):?><div class="portal-alert <?php echo $messageType; ?>"><?php echo htmlspecialchars($message);?></div><?php endif;?><form method="post"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']);?>"><div class="portal-form-grid"><div class="portal-field full"><label for="currentPassword">Current password</label><input type="password" id="currentPassword" name="currentPassword" autocomplete="current-password" required <?php echo !empty($_SESSION['temporary_login'])?'disabled':'';?>></div><div class="portal-field"><label for="newPassword">New password</label><input type="password" id="newPassword" name="newPassword" minlength="8" autocomplete="new-password" required <?php echo !empty($_SESSION['temporary_login'])?'disabled':'';?>></div><div class="portal-field"><label for="confirmPassword">Confirm new password</label><input type="password" id="confirmPassword" name="confirmPassword" minlength="8" autocomplete="new-password" required <?php echo !empty($_SESSION['temporary_login'])?'disabled':'';?>></div><div class="full"><button class="portal-button" type="submit" <?php echo !empty($_SESSION['temporary_login'])?'disabled':'';?>><i class="fa fa-lock"></i> Update password</button></div></div></form></section></div>
<?php require 'includes/layoutBottom.php'; ?>
