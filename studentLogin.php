<?php
	error_reporting(0);
    session_start();
    include('includes/dbconnection.php');

    if(isset($_POST['login']))
    {
        $matricNo=$_POST['matricNo'];
        // $password=md5($_POST['password']);
        $password=$_POST['password'];
        $query = mysqli_query($con,"select * from tblstudent where matricNo='$matricNo' && password='$password'");
        $count = mysqli_num_rows($query);
        $row = mysqli_fetch_array($query);

        if($count > 0)
        {
            $_SESSION['matricNo']=$row['matricNo'];
            $_SESSION['firstName']=$row['firstName'];
            $_SESSION['lastName']=$row['lastName'];

            echo "<script type = \"text/javascript\">
                window.location = (\"student/index.php\")
               </script>";  

            // if($row['roleId'] == 2){ //if user is Hod
                
            //     echo "<script type = \"text/javascript\">
            //     window.location = (\"hod/index.php\")
            //     </script>";  
            // }
            // else if($row['roleId'] == 3){ //if user is Dean
                
            //     echo "<script type = \"text/javascript\">
            //     window.location = (\"dean/index.php\")
            //     </script>";  
            // }
        }
        else
        {
            $errorMsg = "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
        }
    }

?>







<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GITB STUDENT LOGIN / PORTAL</title>

	<meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="img/GITBRoundLogoblack.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style2.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script>
</head>
<body class="bg-light">
	

<div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img class="align-content" src="img/office-buildings-with-modern-architecture.jpg" alt="">
                    </a>
                </div>
                <div class="login-form">
                    <form method="Post" Action="">
                            <?php echo $errorMsg; ?>
                        <strong><h2 align="center">STUDENT LOGIN</h2></strong><hr>
                        <div class="form-group">
                            <label>Matric Number:</label>
                            <input type="text" name="matricNo" Required class="form-control" placeholder="Matric Number">
                        </div>
						<br>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" Required class="form-control" placeholder="Password">
                        </div>
						<br>
                        <div class="checkbox">
                           <label class="pull-left">
                                <a href="index.php">Go Back </a>
                            </label>
							<br>
							<br>
                            <label class="pull-right">
                                <a href="#">Forgot Password?</a>
                            </label>
                        </div>
						<br>
                        <br>
                        <button type="submit" name="login" class="btn btn-success btn-flat m-b-30 m-t-30">Log in</button>

							<div class="social-login-content">
								<div class="social-button">
									<button type="button" class="btn social facebook btn-flat btn-addon mb-3"><i class="ti-facebook"></i>Sign in with facebook</button>
									<button type="button" class="btn social twitter btn-flat btn-addon mt-2"><i class="ti-twitter"></i>Sign in with X formerly knowns as twitter</button>
								</div>
							</div>

							<div class="register-link m-t-15 text-center">
								<p>Don't have an account ? <a href="#"> Sign Up Here</a></p>
							</div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>