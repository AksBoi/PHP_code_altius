<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: index.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous" />
    <link rel="manifest" href="/manifest.json" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
    <script type="text/javascript" async="" src="https://www.googleadservices.com/pagead/conversion_async.js"></script>
    <script src="https://kit.fontawesome.com/ab0ade0b7f.js" crossorigin="anonymous"></script>
    <style media="all" id="fa-v4-font-face"></style>
    <style media="all" id="fa-v4-shims"></style>
    <style media="all" id="fa-main"></style>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open Sans:600,800" rel="stylesheet" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="HandheldFriendly" content="true" />
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="max-age=0" />
    <meta http-equiv="Expires" content="0" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-964634460"></script>
    <script>
        function gtag() {
            dataLayer.push(arguments);
        }
        (window.dataLayer = window.dataLayer || []),
        gtag("js", new Date()),
            gtag("config", "AW-964634460");
    </script>

    <title>Altius Investech</title>
    <link href="css/main.8fab5d24.chunk.css" rel="stylesheet" />
    <link href="css/2.25dcff74.chunk.css" rel="stylesheet" />
    <meta http-equiv="origin-trial" content="A9wkrvp9y21k30U9lU7MJMjBj4USjLrGwV+Z8zO3J3ZBH139DOnCv3XLK2Ii40S94HG1SZ/Zeg2GSHOD3wlWngYAAAB7eyJvcmlnaW4iOiJodHRwczovL3d3dy5nb29nbGV0YWdtYW5hZ2VyLmNvbTo0NDMiLCJmZWF0dXJlIjoiUHJpdmFjeVNhbmRib3hBZHNBUElzIiwiZXhwaXJ5IjoxNjYxMjk5MTk5LCJpc1RoaXJkUGFydHkiOnRydWV9"
    />
    <script src="https://accounts.google.com/gsi/client"></script>
    <style>
        @media print {
            #ghostery-tracker-tally {
                display: none !important;
            }
        }
    </style>
    <style id="googleidentityservice_button_styles"></style>
    <link id="googleidentityservice" type="text/css" media="all" rel="stylesheet" href="https://accounts.google.com/gsi/style" />
</head>

<body>
    

<script src="https://kit.fontawesome.com/ab0ade0b7f.js" crossorigin="anonymous"></script>
    <div id="root" style="height: 100% !important">
        <div class="mainDiv">
            <div class="mt-3">
                <div style="height: 3.5rem">
                    <img class="h-100" src="media/logo.28b683ba.png" alt="altius investech logo" />
                </div>
            </div>
            <div class="row align-items-center mt-2">
                <div class="col-lg-5 col-md-6 col-12 welcomeLoginForm" style="z-index: 999">
                    <div id="LF" style="display: block;">
                        <div>
                            <p class="p-0 m-0 welcome-heading">
                                Welcome to Altius Investech
                            </p>
                            <p class="p-0 m-0 welcome-sub-heading f-14">
                                Buy and Sell Unlisted shares, Pre-IPO Shares and ESOPs
                            </p>
                        </div>

<div id="SF" >
                    <div>
                        <p class="p-0 m-0 welcome-heading">Join Us!</p>
                        <p class="p-0 m-0 welcome-sub-heading f-14">
                            Buy and Sell Unlisted shares, Pre-IPO Shares and ESOPs
                        </p>
                    </div>
                    <form class="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" >
                        <div class="form-group mt-1 mb-2">
                            <p class="m-0 ml-2 mb-2 p-0 f-14 font-weight-bold">Name</p>
                            <input type="text" class="form-control login-screen-input f-14 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" name="username" placeholder="Enter your name" />
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group mt-4 mb-2">
                            <p class="m-0 ml-2 mt-4 mb-2 p-0 f-14 font-weight-bold">
                                Password
                            </p>
                            <input type="password" class="form-control login-screen-input f-14 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" name="password" placeholder="************" />
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div>
                            <p class="m-0 ml-2 mt-4 mb-2 p-0 f-14 font-weight-bold">
                                Confirm Password
                            </p>
                            <input type="password" class="form-control login-screen-input f-14 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" name="confirm_password" placeholder="************" />
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                         </div>
                        </div>
                        <div class="m-0 mt-2 ml-2 p-0 f-12 font-weight-bold" id="arefcd">
                            Have a Referral code? <span class="curPoint" style="color: rgb(0, 159, 227);" onclick="showrefcd()" ;>
                     Click here </span> to enter
                        </div>
                        <div class="form-group mt-1 mb-2 mt-4" id="refcd" style="display: none">
                            <p class="m-0 ml-2 mb-2 p-0 f-14 font-weight-bold">Referral Code</p>
                            <input type="text" class="form-control login-screen-input f-14" name="username" placeholder="Enter the referral code" />
                        </div>
                        <div class="ml-2">
                            <p class="f-12 m-0 p-0 text-danger font-weight-bold"></p>
                        </div>
                        <div class="form-group mt-4 mb-0">
                            <input class="signIn-button curPoint" type='submit' value='Submit' >Sign up</button>
                        </div>
                        <div id="gsignIn" class="form-group mt-2">
                            <button class="googleSignIn-button curPoint">
                  <img class="mr-2" src="media/google.b3136124.png"
                    style="object-fit: contain; height: 1.5rem" />Sign in with Google
                </button>
                        </div>
                        <div id="gsignInAnim" class="form-group mt-2 d-none">
                            <button class="googleSignIn-button gSignIn-parent">
                  <img class="mr-4" src="media/google.b3136124.png"
                    style="object-fit: contain; height: 1.5rem" />
                  <div class="gSignIn-Anim"></div>
                </button>
                        </div>
                        <div>
                            <p class="f-14 m-0 p-0 noAccount">
                                Already have an account?
                                <span class="curPoint" style="color: rgb(0, 159, 227)" >
                                <a href="index.php">
                    Login now</span>
                    </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-5 welcomeImg col-12">
                <div class="blueBack"></div>
                <img src="media/headerImg.11811607.svg" alt="altius investech login" style="object-fit: contain; width: 100%; z-index: 999" />
            </div>
         </div>
         <div class="arrowDivLeft">
            <img src="media/arr1.a7213c0d.svg" />
         </div>
         <div class="investDiv">
            <div class="blueBack"></div>
            <div style="z-index: 999">
                <p class="p-0 m-0 welcome-heading">Invest In</p>
                <div class="row" id="test" style="--marquee-elements: 8">
                    <div class="marquee">
                        <ul class="marquee-content">
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="animCard">
                                    <div class="animImgCard">
                                        <p class="m-0 p-0 f-12">Mobikwik</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="p-0 m-0 mt-4 investDetail">
                    "Altius is India’s leading platform which facilitates buying and selling of Unlisted, Pre-IPO and privately held shares. We ensure liquidity for our clients by providing a two-way (buy & sell) quote on all investments on our platform, fostering trust
                    and transparency."
                </p>
            </div>
         </div>
         <div class="arrowDivRight">
            <img src="media/arr2.a18a818d.svg" />
         </div>
         <div class="investDiv">
            <div class="row mt-4 mx-1 align-items-center">
                <div class="redBack"></div>
                <div class="col-md-7 col-12 p-0" style="z-index: 999">
                    <div class="aboutCont">
                        <p class="m-0 p-0 welcome-heading text-left">
                            Making Investments Quick & Simple
                        </p>
                        <p class="aboutTextpace m-0 p-0 welcome-sub-heading text-left">
                            "At Altius, we value your precious time. So we have built a "
                            <b>secure platform</b> " that allows you to invest seamlessly, "
                            <b>in a few clicks!</b>
                            <br /> " Start you hassle free investments now!"
                        </p>
                        <p class="aboutTextpace m-0 p-0 f-18 font-weight-bold text-left">
                            Invest in just 3 simple steps
                        </p>
                    </div>
                </div>
                <div class="col-md-5 col-12 p-0 howItWorksBox">
                    <div class="howItWorks">
                        <div class="howItWorksCont">
                            <div class="my-0 mx-2 row align-items-center">
                                <div class="col-11 p-0 m-0">
                                    <div class="row m-1 align-items-center">
                                        <div class="col-lg-2 col-md-3 col-2 p-0">
                                            <div class="poFamiliStep">
                                                <img src="media/kyc.13d3fc63.svg" alt="altius investech kyc" />
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-10 p-0">
                                            <p class="m-0 f-14 text-left">
                                                Sign up and Complete KYC
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="howItWorksStepLine">
                                <div class="howItWorksStepCircle"></div>
                            </div>
                            <div class="my-0 mx-2 row align-items-center">
                                <div class="col-11 p-0 m-0">
                                    <div class="row m-1 align-items-center">
                                        <div class="col-lg-2 col-md-3 col-2 p-0">
                                            <div class="poFamiliStep">
                                                <img src="media/gift.477b4583.svg" alt="altius investech place order" />
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-10 p-0">
                                            <p class="m-0 f-14 text-left">Place Order</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="howItWorksStepLine">
                                <div class="howItWorksStepCircle"></div>
                            </div>
                            <div class="my-0 mx-2 row align-items-center">
                                <div class="col-11 p-0 m-0">
                                    <div class="row m-1 align-items-center">
                                        <div class="col-lg-2 col-md-3 col-2 p-0">
                                            <div class="poFamiliStep">
                                                <img src="media/pay.8ec9a6a7.svg" alt="altius investech payment" />
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-10 p-0">
                                            <p class="m-0 f-14 text-left">Complete Payment</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="howItWorksStepLine">
                                <div class="howItWorksStepCircle"></div>
                            </div>
                            <div class="my-0 mx-2 row align-items-center">
                                <div class="col-11 p-0 m-0">
                                    <div class="row m-1 align-items-center">
                                        <div class="col-lg-2 col-md-3 col-2 p-0">
                                            <div class="poFamiliStep">
                                                <img src="media/trade.c35bb2af.svg" alt="altius investech trade" />
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-9 col-10 p-0">
                                            <p class="m-0 f-14 text-left">
                                                Trade gets executed under 24 hours
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <div class="arrowDivLeft">
            <img src="media/arr1.a7213c0d.svg" />
         </div>
         <div class="investDiv">
            <div class="blueBack"></div>
            <div style="z-index: 999">
                <div class="my-4">
                    <p class="p-0 m-0 welcome-heading">Featured In</p>
                </div>
                <div class="row mt-4 align-items-center">
                    <div class="col-3">
                        <img class="featuredInImage curPoint" src="media/economicTime.a039ecc6.svg" alt="altius investech economic times" />
                    </div>
                    <div class="col-3">
                        <img class="featuredInImage curPoint" src="media/economicTime.a039ecc6.svg" alt="altius investech economic times" />
                    </div>
                    <div class="col-3">
                        <img class="featuredInImage curPoint" src="media/economicTime.a039ecc6.svg" alt="altius investech economic times" />
                    </div>
                    <div class="col-3">
                        <img class="featuredInImage curPoint" src="media/economicTime.a039ecc6.svg" alt="altius investech economic times" />
                    </div>
                </div>
            </div>
         </div>
         <div class="arrowDivRight">
            <img src="media/arr2.a18a818d.svg" />
         </div>
         <div class="investDiv">
            <div class="redBack"></div>
            <div style="z-index: 999">
                <div class="my-4">
                    <p class="p-0 m-0 welcome-heading">Get in Touch</p>
                </div>
                <div class="row getInTouch align-items-center">
                    <div class="col-md-4 col-3 p-0 m-0 curPoint">
                        <img class="getInTouchIcon" src="media/WhatsApp.fd95dd24.svg" alt="altius investech whatsapp" />
                        <p class="m-0 font-weight-bold">WhatsApp Us</p>
                        <p class="m-0 lightColorText">+91-9038517269</p>
                    </div>
                    <div class="col-md-4 col-6 p-0 m-0 curPoint">
                        <img class="getInTouchIcon" src="media/Mail.be05af82.svg" alt="altius investech mail" />
                        <p class="m-0 font-weight-bold">Email Us</p>
                        <p class="m-0 lightColorText">info@altiusinvestech.com</p>
                    </div>
                    <div class="col-md-4 col-3 p-0 m-0 curPoint">
                        <img class="getInTouchIcon" src="media/Phone.36baa0ad.svg" alt="altius investech phone" />
                        <p class="m-0 font-weight-bold">Call Us</p>
                        <p class="m-0 lightColorText">+91-9038517269</p>
                    </div>
                </div>
            </div>
         </div>
         <hr class="hr" />
         <div class="footer">
            <div class="row m-1 mb-5">
                <div class="col-md-4 p-0 m-0">
                    <p class="f-12 p-0 m-0 mb-1 footerHeading">Altius Investech</p>
                    <p class="f-12 p-0 mt-3 mb-1 font-weight-bold">Social</p>
                    <p class="f-18 p-0 mb-3 font-weight-bold">
                        <a class="text-dark" target="_blank" href="https://www.facebook.com/altiusinv">
                            <i class="fa fa-facebook-square mr-2" aria-hidden="true">

                </i>
                        </a>
                        <a class="text-dark" target="_blank" href="https://twitter.com/unlistedshares1">
                            <i class="fa fa-twitter mr-2" aria-hidden="true">

                </i>
                        </a>
                        <a class="text-dark" target="_blank" href="https://www.linkedin.com/company/altius-investech/">
                            <i class="fa fa-linkedin-square mr-2" aria-hidden="true">

                </i>
                        </a>
                        <a class="text-dark" target="_blank" href="https://www.instagram.com/altius.investech/">
                            <i class="fab fa-instagram" aria-hidden="true"> </i>
                        </a>
                    </p>
                    <p class="f-12 p-0 mt-5 mb-1 font-weight-bold">
                        Registered Office
                    </p>
                    <p class="f-12 p-0 mb-1 lightColorText">
                        "2nd Floor, Room 201, 73A, Ganesh Chandra Ave, Chandni Chawk, Bowbazar, Kolkata, West Bengal 700013"
                    </p>
                </div>
                <div class="col-md-5 col-6 p-0 m-0 footerConv">
                    <p class="f-12 p-0 m-0 mb-1 footerHeading">Company</p>
                    <div class="row m-0 p-0">
                        <div class="col-md-6 col-12 pl-0 lightColorText">
                            <p class="f-12 p-0 m-0 mb-1">
                                <a target="_blank" href="https://altiusinvestech.com/" class="lightColorText">Home</a>
                            </p>
                            <p class="f-12 p-0 m-0 mb-1">
                                <a target="_blank" href="https://altiusinvestech.com/about" class="lightColorText">About us</a>
                            </p>
                            <p class="f-12 p-0 m-0 mb-1">
                                <a target="_blank" href="https://altiusinvestech.com/works" class="lightColorText">How it works</a>
                            </p>
                            <p class="f-12 p-0 m-0 mb-1">
                                <a target="_blank" href="https://altiusinvestech.com/press" class="lightColorText">Press</a>
                            </p>
                        </div>
                        <div class="col-md-6 p-0 col-12 lightColorText">
                            <p class="f-12 p-0 m-0 mb-1">
                                <a target="_blank" href="https://altiusinvestech.com/blog" class="lightColorText">Blog</a>
                            </p>
                            <p class="f-12 p-0 m-0 mb-1">
                                <a target="_blank" href="https://altiusinvestech.com/contact" class="lightColorText">Contact us</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 p-0 m-0 footerConv">
                    <p class="f-12 p-0 m-0 mb-1 footerHeading">Quick links</p>
                    <p class="f-12 p-0 m-0 mb-1 lightColorText">
                        <a target="_blank" href="https://altiusinvestech.com/blog/a-complete-walkthrough-of-our-trading-portal/" class="lightColorText">A Complete Walkthrough Of Our Trading Portal</a>
                    </p>
                    <p class="f-12 p-0 m-0 mb-1 lightColorText">
                        <a target="_blank" href="https://altiusinvestech.com/blog/capital-gain-on-sale-of-unlisted-shares/" class="lightColorText">Capital Gain on Sale of Unlisted Shares</a>
                    </p>
                    <p class="f-12 p-0 m-0 mb-1 lightColorText">
                        <a target="_blank" href="https://altiusinvestech.com/blog/how-to-transfer-unlisted-shares/" class="lightColorText">How to transfer unlisted shares?</a>
                    </p>
                    <p class="f-12 p-0 m-0 mb-1 lightColorText">
                        <a target="_blank" href="https://altiusinvestech.com/blog/income-tax-on-unlisted-shares/" class="lightColorText">Income Tax on Unlisted Shares</a>
                    </p>
                </div>
            </div>
            <p class="f-10 m-0 pb-1 text-center lightColorText">
                All rights reserved. Copyright © 2022 Altius Investech
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="/js/2.c1a8c7b6.chunk.js"></script>
    <script src="/js/main.60612bd7.chunk.js"></script>
    <script src="/js/script.js"></script>

</body>