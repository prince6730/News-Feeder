<?php
ob_start();
session_start();
require_once('../include/db.php');

if(isset($_POST['submit'])){
   $username = mysqli_real_escape_string($con, strtolower($_POST['username']));
   $password = mysqli_real_escape_string($con, $_POST['password']);
    
    $check_username_query = "SELECT * FROM users WHERE username = 
    '$username'";
    $check_username_run = mysqli_query($con, $check_username_query);
    if(mysqli_num_rows($check_username_run) > 0){
        $row = mysqli_fetch_array($check_username_run);
        
        $db_username = $row['username'];
        $db_password = $row['password'];
        $db_role = $row['role'];
        $db_author_image = $row['image'];
        
        $password = crypt($password, $db_password);
                
        if($username == $db_username && $password == $db_password){
            header('Location: index.php');
                
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $db_role;
            $_SESSION['author_image'] = $db_author_image;
        }
        else{
            $error = "Wrong Username or Password";
        }
    }
    else{
        $error = "Wrong Username or Password";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="img/favicon1.jpg">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.4/examples/signin/">

    <title>login | News Feeder Admin</title>

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    
    <link href="css/login.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">Login Panel</h2>
        
        <label for="inputEmail" class="sr-only">Username</label>
        <input type="text" id="inputEmail" name="username" class="form-control" placeholder="Username" required autofocus>
        
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        
        <label>
          <?php
            if(isset($error)){
              echo "$error";
              }
            ?>
            </label>
      
        <input type="submit" name="submit" value="Sign In" class="btn  btn-lg btn-primary btn-block" >
      </form>

    </div>


    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
