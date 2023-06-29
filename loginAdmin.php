<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="./css/main.css" rel="stylesheet" />
    <title>Login</title>
</head>

<body>
    <div class="w-full h-screen flex justify-center items-center">
        <div class="h-max w-1/2 rounded-xl shadow-lg md:grid grid-cols-2 sm:block">
            <div class="bg-accent md:rounded-l-2xl text-white pt-10">
                <h2 class="text-xl text-center">We Still Connected</h2>
                <img src="./assets/graduate_logo.png" alt="">
            </div>
            <div class="flex flex-col gap-2 p-3 items-center">
                <p class="text-center font-semibold text-accent text-lg mt-10">Who are you?</p>
                <button id="univAdmin" value="univAdmin" onclick="redirect(this)"
                    class="text-greyish_black font-medium py-2 px-2 bg-slate-300 shadow-lg rounded w-2/3 hover:bg-slate-400">
                    University Admin
                </button>
                <button id="colAdmin" value="colAdmin" onclick="redirect(this)"
                    class="text-greyish_black font-medium py-2 px-2 bg-slate-300 shadow-lg rounded w-2/3 hover:bg-slate-400">
                    College Admin Coordinator
                </button>
            </div>
        </div>
    </div>


    <script>
        function redirect(element) {

            //check where the user to be redirect
            let loginRedirect = (element.value === "univAdmin") ? "./admin/loginAdmin.php" : "rar";

            window.location.href = loginRedirect
        }
    </script>
</body>

</html>