<!-- PHP Code Start -->
<?php
    /*-- Starting session --*/
    session_start();

    /*-- Login Access Control Process --*/
    if(isset($_SESSION["userSignin"]) && $_SESSION["userSignin"] === true){
        if ($_SESSION["userRole"] == "Admin") {
            header('Location: admin_home.php');
        } else {
            header('Location: user_home.php');
        }
    }

    /*-- Procedure with file --*/
    $filename = "C:/xampp/htdocs/ostad_php_laravel_assignments/Module_5/datafile/user.txt";
    $fp = fopen($filename, "a+");

    $message = "";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signin"])){
    /*-- Getting Values from user --*/
        $userName     = trim($_POST['userName']);
        $userEmail    = trim($_POST['userEmail']);
        $userPassword = trim($_POST['userPassword']);
     
    /*-- Validation Process --*/
        if(empty($userName) || empty($userEmail) || empty($userPassword)){
            $message = "<div class='alert alert-danger m-3'><strong>Error! </strong>Missing Sufficient Information</div>";
        } elseif(!filter_var(trim($userEmail), FILTER_VALIDATE_EMAIL)){
            $message = "<div class='alert alert-danger m-3'><strong>Error! </strong>Invalid Email Address</div>";
        } else{
        /*-- Password Encryption --*/ 
            $userPassword = md5($userPassword);

        /*-- Getting file data as array --*/
            $data = file($filename);

        /*-- Checking user data available or not --*/
            for($i = 0; $i < count($data); $i++){
                $singleUserData = explode(",", $data[$i]);
                if($singleUserData[3] != $userName || $singleUserData[4] != $userEmail || trim($singleUserData[5]) != $userPassword){
                    $message = "<div class='alert alert-danger m-3'><strong>Error! </strong>Data not found</div>";
                } else{
                /*-- Starting session and assigning value --*/
                    $_SESSION["userSignin"]   = true;
                    $_SESSION["userId"]       = $i;
                    $_SESSION["userRole"]     = $singleUserData[0];
                    $_SESSION["firstName"]    = $singleUserData[1];
                    $_SESSION["lastName"]     = $singleUserData[2];
                    $_SESSION["userName"]     = $singleUserData[3];
                    $_SESSION["userEmail"]    = $singleUserData[4];
                    $_SESSION["userPassword"] = $singleUserData[5];
                /*-- Redirecting conditionally after signin --*/
                    if($_SESSION["userRole"] == "Admin"){
                        echo"<script>window.location='admin_home.php'</script>";
                    } else{
                        echo"<script>window.location='user_home.php'</script>";
                    }
                }
            }
        }
    }
?>
<!-- PHP Code End -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - User Authentication and Role Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row d-flex justify-content-center min-vh-100 m-5">
            <div class="col-lg-8 col-md-8 col-12 p-2">
            <!-- Card Start -->
            <div class="card rounded">
                <div class="card-header text-white fw-bold">
                <!-- Card Title Start -->
                    <h3 class="card-title p-3 m-auto text-center">Sign In</h3>
                <!-- Card Title End -->
                </div>
                
                <!-- Message Start -->
                <?php
                    if(!empty($message)){
                        echo $message;
                    }
                ?>
                <!-- Message End -->

                <div class="card-body p-3">
                <!-- Form Start -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="row justify-content-center fs-5">
                            <div class="col-12 mb-2">
                                <input type="text" name="userName" placeholder="Username" class="form-control fs-5">
                            </div>

                            <div class="col-12 mb-2">
                                <input type="text" name="userEmail" placeholder="Email" class="form-control fs-5">
                            </div>

                            <div class="col-12 mb-2">
                                <input type="password" name="userPassword" placeholder="Password" class="form-control fs-5">
                            </div>
                            
                            <div class="col-12 mt-5">
                                <button class="btn btn-primary shadow-none text-white w-100 fs-5" type="submit" name="signin">Sign In</button>
                            </div>

                            <div class="col-6 mt-5">
                                <p class="text-center">Forgot Password? <a href="#" class="text-decoration-none fw-bold">Find</a></p>
                            </div>

                            <div class="col-6 mt-5">
                                <p class="text-center">Don't have an account? <a href="signup.php" class="text-decoration-none fw-bold">Create</a></p>
                            </div>
                        </div>
                    </form>
                <!-- Form End -->
                </div>
            </div>
        <!-- Card End -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>