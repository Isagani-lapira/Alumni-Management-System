<!DOCTYPE html>
<html lang="en">
<!-- update -->
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../css/main.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
  <title>BulSuan Landing Page</title>

  <link rel="icon" href="../assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
  <style>
    body {
      background-image: url('../assets/bg.jpg');
      background-color: rgba(0, 0, 0, 0.5);
      /* Adjust the alpha value between 0 and 1 for desired opacity */
      background-blend-mode: multiply;
      background-size: cover;
    }
  </style>
</head>

<body>
  <div class="w-full h-screen flex justify-center items-center">
    <div class="md:h-max sm:h-14 w-1/2 rounded-xle md:grid grid-cols-2 sm:block text-gray-600">
      <div class="bg-accent md:rounded-l-2xl text-white pt-10 h-full relative">
        <h2 class="Lobster text-xl text-center">"We Still Connected"</h2>
        <img id="graduateLogo" class="relative" src="../assets/graduate_logo.png" alt=""> <!--make it relative-->
      </div>

      <!-- login -->
      <div id="loginPanel" class=" p-3 rounded-r-2xl relative bg-white">
        <h1 class="text-4xl font-bold text-greyish_black text-center w-2/3 block mx-auto">Welcome Back BulSuan!
        </h1>
        <div>
          <form id="loginForm" class="mt-2 p-3 flex flex-col gap-1">
            <p id="errorMsg" class="text-sm text-accent hidden">
              <span class="font-bold">login Failed:</span> Incorrect username/password
            </p>
            <label for="username">Username</label>
            <input name="username" class="logInput border border-gray-400 rounded-md p-2 outline-none italic-placeholder" placeholder="e.g patrickPron625" type="text" id="username">
            <label for="password">Password</label>
            <div class="pass_details border border-gray-400 rounded-md p-2 flex items-center">
              <input name="password" class="logInput flex-1 outline-none italic-placeholder" placeholder="e.g patrickPron625" type="password" id="password">
              <span id="toggleLoginPassword" class="fa-regular fa-eye-slash cursor-pointer" style="color: #969696;"></span>
            </div>
            <a href="../password-recovery/" class="italic text-accent text-sm text-end">Forgot password?</a>
            <button type="submit" class="rounded-md bg-accent text-white py-3 mt-3 hover:bg-darkAccent">
              Sign in
            </button>
          </form>
          <p id="registerBtn" class="text-center absolute bottom-1 w-full cursor-pointer md:text-base sm:text-xs">Don't
            have an account? <span class="text-accent font-semibold"><a href="../student-alumni/registrationForm.php">Register here</a></span></p>
        </div>
      </div>

    </div>

  </div>

  <script src="../student-alumni/js/login-register.js"></script>
</body>

</html>