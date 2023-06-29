<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <title>University Admin Login</title>
</head>

<body>
    <div class="w-full h-screen flex justify-center items-center">
        <div class="md:h-max sm:h-14 w-1/2 rounded-xl shadow-lg md:grid grid-cols-2 sm:block">
            <div class="bg-accent md:rounded-l-2xl text-white pt-10">
                <h2 class="text-xl text-center">We Still Connected</h2>
                <img src="../assets/graduate_logo.png" alt="">
            </div>

            <div class="p-3">
                <h1 class="text-2xl font-bold text-greyish_black text-center w-2/3 block mx-auto">Welcome back Admin!
                </h1>
                <div>
                    <form id="loginForm" class="mt-2 p-3 flex flex-col gap-1">
                        <p class="text-sm text-accent hidden"><span class="font-bold">login Failed:</span> Incorrect
                            username/password</p>
                        <label>Username</label>
                        <input name="username" class=" border border-gray-400 rounded-md p-2 outline-none"
                            placeholder="e.g patrickPron625" type="text">
                        <label>Password</label>
                        <div class="pass_details border border-gray-400 rounded-md p-2 flex items-center">
                            <input name="password" class="flex-1 outline-none" placeholder="e.g patrickPron625"
                                type="text">
                            <span class="fa-regular fa-eye-slash cursor-pointer" style="color: #969696;"></span>
                        </div>
                        <p class="italic text-accent text-sm text-end">Forgot password?</p>
                        <button type="submit" class="rounded-md bg-accent text-white py-3 mt-3 hover:bg-darkAccent">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/login.js"></script>
</body>

</html>