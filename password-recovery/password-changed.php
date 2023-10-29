<!-- update -->
<?php require_once "controller.php";
?>
<?php
if ($_SESSION['info'] == false) {
    header('Location: ../student-alumni/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
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

    <link rel="stylesheet" href="../css/main.css">


</head>

<body>
    <div class="daisy-hero min-h-screen bg-base-200">
        <div class="daisy-hero-content flex-col ">
            <div class="daisy-card flex-shrink-0 w-full max-w-lg shadow-2xl bg-base-100">
                <form class="daisy-card-body">
                    <h1 class="font-bold text-xl text-center">Successfully Reset Your Password</h1>
                    <p class="py-6">You may now login with your freshly made password!</p>
                    <div class="daisy-form-control mt-4">
                        <a href="../student-alumni/login.php" class="daisy-btn daisy-btn-neutral bg-accent">Return to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>