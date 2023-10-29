<!-- update -->
<?php
require_once "controller.php";

?>
<?php
$email = $_SESSION['email'];
if ($email == false) {
    header('Location: ../student-alumni/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> -->

    <!--   Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <!-- Font-awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



    <!-- Style Sheeet -->
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <div class="daisy-navbar bg-base-100 p-4">
        <img class="w-32 h-16" src="../images/BulSU-Connect-Logo.png" alt="">
    </div>
    <div class="daisy-hero min-h-screen">
        <div class="daisy-hero-content flex-col ">
            <div class="daisy-card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">

                <form class="daisy-card-body" action="index.php" method="POST">
                    <?php
                        if (isset($_SESSION['info'])) {
                        ?>
                            <div class="daisy-alert daisy-alert-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>
                                    <?php echo $_SESSION['info']; ?>
                                </span>
                            </div>

                        <?php
                        }
                        ?>
                        <?php

                        if (isset($errors) &&  count($errors) > 0) {
                        ?>
                            <div class="daisy-alert daisy-alert-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>

                                    <?php
                                    foreach ($errors as $showerror) {
                                        echo $showerror;
                                    }
                                    ?>
                                </span>
                            </div>

                        <?php
                        }
                    ?>

                    <h1 class="text-3xl font-bold text-accent">Verify Your Email</h1>
                    <p class="py6 daisy-prose">Please Enter The 6 Digit Code Sent To
                        <span class="text-accent">'<?= isset($_SESSION['email']) ?  $_SESSION['email'] : 'No email found in session.' ?>'</span>
                    </p>
                    <div class="daisy-form-control">
                        <label class="daisy-label">
                            <span class="daisy-label-text">Enter Code</span>
                        </label>

                        <input class="form-control daisy-input daisy-input-bordered
                        [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none
                        " type="number" name="otp" maxlength="6" placeholder="Enter code" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="daisy-form-control hover:underline">
                        <label class="daisy-label">
                            <a href="index.php" class="daisy-label-text-alt text-accent ">Resend Code</a>
                        </label>
                    </div>
                    <div class="daisy-form-control mt-6">
                        <button class="daisy-btn bg-accent text-white hover:bg-darkAccent" type="submit" name="check-reset-otp" value="Submit">Verify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</body>

</html>