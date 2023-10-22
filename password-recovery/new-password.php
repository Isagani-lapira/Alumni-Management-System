<?php //require_once "controller.php"; 
?>
<?php
// $personID = $_SESSION['personID'];
// if ($personID == false) {
//     header('Location: login-user.php');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="../css/main.css">
    <!--   Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <!-- Font-awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <div class="daisy-hero min-h-screen bg-base-200">
        <div class="daisy-hero-content flex-col lg:max-w-lg">
            <!-- <div class="text-center lg:text-left">
                <h1 class="text-5xl font-bold">Forgot Password</h1>
                <p class="py-6 max-w-prose">
                    Enter your email address to reset your password.
                </p>
            </div> -->
            <div class="daisy-card flex-shrink-0 w-full lg:w-[200%] max-w-sm lg:max-w-lg shadow-2xl bg-base-100">
                <form class="daisy-card-body" method="POST" action="index.php">
                    <h1 class="text-xl font-bold">Reset Password</h1>

                    <!-- <p class="py6"></p> -->
                    <?php
                    if (isset($errors) && count($errors) > 0) {
                    ?>
                        <div class="daisy-alert daisy-alert-error">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <span>

                                <?php
                                foreach ($errors as $error) {
                                    echo $error;
                                }
                                ?>
                            </span>
                        </div>

                    <?php
                    }
                    ?>

                    <div class="daisy-form-control relative">
                        <label class="daisy-label">
                            <span class="daisy-label-text">New Password</span>
                        </label>
                        <div>
                            <input type="password" class="daisy-input daisy-input-bordered w-full" required name="password" placeholder="Create new password" />
                            <i class="far -ml-8 fa-eye " id="togglePassword"></i>
                        </div>

                    </div>
                    <div class=" daisy-form-control">
                        <label class="daisy-label">
                            <span class="daisy-label-text">Confirm New Password</span>
                        </label>
                        <div><input type="password" class="daisy-input daisy-input-bordered w-full" name="cpassword" placeholder="Confirm your password" required />
                            <i class="far -ml-8 fa-eye " id="toggleCPassword"></i>
                        </div>

                    </div>
                    <div class="daisy-form-control mt-6">
                        <button class="daisy-btn daisy-btn-primary" name="change-password" value="Change">Save New Password</button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <!-- <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">New Password</h2>
                    <?php
                    if (isset($_SESSION['info'])) {
                    ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($errors) && count($errors) > 0) {
                    ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach ($errors as $showerror) {
                                echo $showerror;
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Create new password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div> -->

</body>

</html>