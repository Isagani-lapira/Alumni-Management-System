<?php

$title = 'Alumni of the Year';
require('./partials/head.php');
?>


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

<section class="container mx-auto py-12 ">

    <div class="grid w-full grid-flow-row md:grid-flow-col justify-between">

        <div class="space-y-7">

            <h2 class="font-bold text-4xl ">Skills</h2>
            <div class="flex items-center space-x-2">
                <div class="text-white bg-secondary rounded-full w-14 h-14 flex items-center justify-center">
                    <span>✔</span>
                </div>
                <p>We connect our customers with the best.</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="text-white bg-secondary rounded-full w-14 h-14 flex items-center justify-center">
                    <span>✔</span>
                </div>
                <p>Advisor success customer launch party.</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="text-white bg-secondary rounded-full w-14 h-14 flex items-center justify-center">
                    <span>✔</span>
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
<section>
    <h2>Education</h2>
    <p>College</p>
    <p>Bulacan State University</p>
    <p>Highschool</p>
    <p>Marcelo H. Del Pilar National Highschool</p>
    <p>Elementary </p>
    <p>Caniogan Elementary School</p>
</section>

<section>
    <h2>For more details you may visit me at: </h2>


</section>
<?php
require('./partials/footer.php');
