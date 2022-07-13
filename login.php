<?php
session_start();

//isset checks the variable is defined and is not NULL
//=== true checks wheather the variable is true or not 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    //vulnerable 
    header("location: index.html")
    exit;
}
require_once "config.php";

$username = $password = '';
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "please Enter Your Username. ";
    }
    else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password ";
    }
    else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
       $sql = "SELECT id, username, password FROM users WHERE username= ?";
       //prepare
       //bind params
       //execute
       //store result
       //num rows
       //bind results
       //fetch 
       

       if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_params($stmt,"s",$param_username, $param_password);
        $param_username = $username;
        
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)==1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashpassword);
                if(mysqli_stmt_fetch($stmt)){
                    if(password_verify($password, $hashpassword)){
                        session_start();

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;

                        header("location: welcome.php");
                    }
                    else{
                        $login_err = "Invalid username or password";
                    }
                } else{
                    $login_err = "Invalid credentials , Renter";
                }
            } else {
                echo "Oops! something went wrong!. Please Renter";
            }

            mysqli_stmt_close($stmt);
        }
       }
    }

    mysqli_close($link);
}
?>