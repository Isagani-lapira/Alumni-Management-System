<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="./style.css">
    <title>Login</title>
</head>

<body class="flex flex-col h-screen">
    <nav class="px-8 flex-initial">

        <img class="w-24" src="../assets/logo-cict.png" alt="logo of the CICT college">
    </nav>

    <div class="flex-1 grid place-content-center">
        <!-- Login Main -->
        <main class=" border-black border-2 container mx-auto flex h-[35rem] rounded-lg">
            <!-- we are still connected -->
            <div class="w-72 bg-darkAccent rounded-l-lg relative">
                <p class="italic text-white text-2xl text-center pt-12">We are still connected!</p>
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
                        </div>


                        <button type="submit" class="bg-darkAccent hover:bg-accent text-white py-2 px-2 w-full rounded-md ">Sign In</button>
                    </div>
                </form>

            </div>

        </main>




    </div>
</body>

</html>