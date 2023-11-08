<?php

$title = 'Alumni of the Year';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?> </title>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jquery cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <link href="css/main.css" rel="stylesheet" />
    <link href="style/homepage.css" rel="stylesheet" />
    <link rel="icon" href="./assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
</head>
<!-- TODO Make it responsive -->

<body class="relative">

    <!-- heading image -->
    <section class="relative">
        <!-- Image heading -->
        <img class="w-full max-w-full" src="./assets/alumni-heading.png" alt="">
        <!-- Dropdown menu -->
        <button class="absolute font-bold right-12 top-12 text-white bg-accent hover:bg-secondary focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center" type="button" data-dropdown-toggle="dropdown">
            2023
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>

        </button>
    </section>

    <!-- quotation -->
    <section class="container mx-auto py-12 text-center ">
        <q class=" italic text-2xl">
            There are no secrets to success. It is the result of preparation,
            hard work, and learning from failure.
        </q>
        <p class="text-blue-600 italic">Orlando Pimentel 2013</p>
    </section>

    <!-- testimonials -->
    <section class="bg-accent py-12">
        <div class="container mx-auto  space-y-12">
            <h2 class="text-4xl text-white font-bold ">Testimonials</h2>
            <div class="grid gap-3 justify-center md:grid-flow-col md:auto-cols-fr">
                <!-- testimonial card -->
                <div class="bg-white text-black p-8 rounded-lg max-w-sm space-y-4">
                    <q class="max-w-prose text-2xl">Very responsible person especially on work. A type of person who's eager to learn</q>
                    <div class="flex items-center gap-2 ">
                        <img src="./assets/avatar-prof.png" alt="" class="w-12">
                        <div>
                            <div class="text-lg font-bold text-black">Wilhelm Miranda</div>
                            <p class="text-slate-500">Full Stack Developer</p>
                        </div>

                    </div>
                </div>
                <!-- testimonial card -->
                <div class="bg-white text-black p-8 rounded-lg max-w-sm space-y-4">
                    <q class="max-w-prose text-2xl">Very responsible person especially on work. A type of person who's eager to learn</q>
                    <div class="flex items-center gap-2 ">
                        <img src="./assets/avatar-prof.png" alt="" class="w-12">
                        <div>
                            <div class="text-lg font-bold text-black">Wilhelm Miranda</div>
                            <p class="text-slate-500">Full Stack Developer</p>
                        </div>

                    </div>
                </div>
                <!-- testimonial card -->
                <div class="bg-white text-black p-8 rounded-lg max-w-sm space-y-4">
                    <q class="max-w-prose text-2xl">Very responsible person especially on work. A type of person who's eager to learn</q>
                    <div class="flex items-center gap-2 ">
                        <img src="./assets/avatar-prof.png" alt="" class="w-12">
                        <div>
                            <div class="text-lg font-bold text-black">Wilhelm Miranda</div>
                            <p class="text-slate-500">Full Stack Developer</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Achievements -->
    <section class="container mx-auto py-12 space-y-8">
        <h2 class="text-center text-3xl font-bold">Achievements</h2>

        <div class="container  mx-auto md:max-w-5xl grid md:auto-cols-fr md:grid-flow-col">

            <!-- card -->
            <div class="bg-green-700 text-white p-6">
                <div class="text-3xl font-bold text-center">Employee of the Year 4X</div>
                <p class="max-w-prose">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

            </div>

            <!-- card -->
            <div class="bg-yellow-500 text-white p-6">
                <div class="text-3xl font-bold text-center">Establish 10 Businesses</div>
                <p class="max-w-prose">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

            </div>

            <!-- card -->
            <div class="bg-purple-500 text-white p-6">
                <div class="text-3xl font-bold text-center">Promoted Senior after 2 years of Working</div>
                <p class="max-w-prose">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

            </div>

        </div>

    </section>

    <!-- Skills  -->
    <section class="container mx-auto py-12 ">

        <div class="grid w-full grid-flow-row md:grid-flow-col justify-between">

            <div class="space-y-7">

                <h2 class="font-bold text-4xl ">Skills</h2>
                <!-- check -->
                <div class="flex items-center space-x-2">
                    <div class="text-white bg-secondary rounded-full w-14 h-14 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>
                    <p>We connect our customers with the best.</p>
                </div>

                <!-- check -->
                <div class="flex items-center space-x-2">
                    <div class="text-white bg-secondary rounded-full w-14 h-14 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>
                    <p>Advisor success customer launch party.</p>
                </div>

                <!-- check -->
                <div class="flex items-center space-x-2">
                    <div class="text-white bg-secondary rounded-full w-14 h-14 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>
                    <p>Business-to-consumer long tail.</p>
                </div>
            </div>

            <div class="
        rounded-full
        border-r-[20px] border-accent
        "> <img class="max-w-lg object-contain " src="./assets/alumni-pic2.png" alt=""></div>
        </div>
    </section>

    <!-- Education  -->
    <section class="container mx-auto py-12 space-y-12">
        <h2 class="font-bold text-4xl">Education</h2>
        <p><span class="font-bold">College:</span> Bulacan State University</p>
        <p><span class="font-bold">Highschool:</span> Marcelo H. Del Pilar National Highschool</p>
        <p><span class="font-bold">Elementary:</span> Caniogan Elementary School</p>
    </section>

    <!-- more details -->
    <section class="container mx-auto py-12 space-y-12">
        <h2 class="font-bold text-3xl text-grayish text-center">For more details you may visit me at: </h>

    </section>

    <!-- More details section -->
    <section class="bg-grayish py-12">

        <div class="container items-center justify-around grid grid-flow-col text-white">
            <img src="./assets/footer-img.png" alt="">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <polyline points="3 7 12 13 21 7" />
                    </svg>
                    <p>orlandopimentel12@gmail.com</p>
                </div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-linkedin" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="4" y="4" width="16" height="16" rx="2" />
                        <line x1="8" y1="11" x2="8" y2="16" />
                        <line x1="8" y1="8" x2="8" y2="8.01" />
                        <line x1="12" y1="16" x2="12" y2="11" />
                        <path d="M16 16v-3a2 2 0 0 0 -4 0" />
                    </svg>


                    <p>Orlando Pimentel</p>
                </div>
                <div class="flex items-center gap-2">

                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                    </svg>
                    <p>Orlando_Pimentel</p>
                </div>
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="4" y="4" width="16" height="16" rx="4" />
                        <circle cx="12" cy="12" r="3" />
                        <line x1="16.5" y1="7.5" x2="16.5" y2="7.501" />
                    </svg>
                    <p>OrlandoPimen</p>
                </div>
            </div>
        </div>

    </section>

</body>

</html>