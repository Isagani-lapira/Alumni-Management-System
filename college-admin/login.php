<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="./style.css">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <title>Login</title>
</head>

<body class="flex flex-col h-screen">
    <nav class="px-8 flex-initial">

        <img class="w-24" src="../assets/logo-cict.png" alt="logo of the CICT college">
    </nav>

    <div class="flex-1 grid place-content-center">
        <!-- Login Main -->
        <main class=" border container mx-auto flex h-[35rem] rounded-lg shadow-2xl">
            <!-- we are still connected -->
            <div class="w-96 bg-darkAccent rounded-l-lg relative px-8">
                <p class="italic text-white text-2xl text-center pt-12">We still connected!</p>
                <img src="../assets/login-graduated.png" alt="" class="absolute bottom-0 left-0">

            </div>
            <!-- actual content -->
            <div class="w-[40rem] px-8 py-2">
                <h1 class="font-black text-center text-3xl">Welcome Back Admin!</h1>
                <form action="">
                    <div class="py-16 w-3/5 mx-auto space-y-4">
                        <div class="form-control">
                            <label for="username" class="block">Username</label>
                            <input type="text" name="username" id="username" placeholder="e.g. patrickPron625" class="input-text w-full">
                        </div>
                        <div class="form-control">
                            <label for="password" class="block">Password</label>
                            <input type="text" name="password" id="password" placeholder="e.g. myNameis@Pronuevo" class="input-text w-full">
                            <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
                            <div class="text-right"><a href="TODO/forgot/password" class="italic text-accent hover:text-darkAccent  ">Forgot password?</a></div>
                        </div>



                        <button type="submit" class="bg-darkAccent hover:bg-accent text-white py-2 px-2 w-full rounded-md ">Sign In</button>
                    </div>
                </form>

            </div>

        </main>



        <script>
            $(document).ready(function() {
                // const togglePassword = document.querySelector('#togglePassword');
                // const password = document.querySelector('#id_password');

                // togglePassword.addEventListener('click', function(e) {
                //     // toggle the type attribute
                //     const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                //     password.setAttribute('type', type);
                //     // toggle the eye slash icon
                //     this.classList.toggle('fa-eye-slash');
                // });

                $('#togglePassword').click(function(e) {

                    // toggle password type
                    const type = $('#password').get(0).type === 'password' ? 'text' : 'password';
                    $('#password').attr('type', type);
                    $(this).toggleClass("fa-eye-slash");
                    $(this).toggleClass("fa-eye");



                })
            })
        </script>

    </div>
</body>

</html>