<?php require_once "controller.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <!-- Style Sheeet -->
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

<body class="min-h-screen relative">
    <div class="daisy-navbar bg-base-100 p-4 absolute h-16 top-0 inset-0">
        <img class="w-32 h-16" src="../images/BulSU-Connect-Logo.png" alt="">
    </div>
    <div class="daisy-hero min-h-screen">
        <div class="daisy-hero-content flex-col lg:flex-row">
            <div class="daisy-card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">
                <div class="daisy-card-body">
                    <div class="text-center lg:text-left space-y-2">
                        <h1 class="text-3xl font-bold text-accent">Forgot Password</h1>
                        <p class="max-w-prose">
                            Enter your email address to reset your password.
                        </p>
                    </div>
                    <form method="POST" action="index.php">
                        <?php
                        if (count($errors) > 0) {
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

                        <div class="daisy-form-control">
                            <label class="daisy-label">
                                <span class="daisy-label-text">Email</span>
                            </label>
                            <input type="email" placeholder="email" class="daisy-input daisy-input-bordered" required type="email" name="email" placeholder="Enter email address" value="<?php echo $email ?>" />
                        </div>
                        <div class="daisy-form-control">
                            <!-- <label class="daisy-label">
                                <span class="daisy-label-text">Password</span>
                            </label>
                            <input type="password" placeholder="password" class="daisy-input daisy-input-bordered" required /> -->
                        </div>
                        <div class="button space-y-2">
                            <div class="daisy-form-control mt-6">
                                <button class="daisy-btn bg-accent text-white hover:bg-darkAccent" value="Continue" name="check-email">Send Code to Email</button>
                            </div>
                            <div class="daisy-form-control">
                                <a href="../student-alumni/login.php" class="daisy-btn ">Login Instead</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>