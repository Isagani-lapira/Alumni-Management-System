<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="css/main.css" rel="stylesheet" />
  <title>BulSu Alumni</title>
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
</head>
<!-- TODO Make it responsive -->

<body class="">
  <!-- header -->
  <header class="bg-white flex justify-between items-center px-12 py-6">
    <img class="w-20 h-full" src="./assets/bulsu-logo.png" alt="Bulcan State University Logo" />
    <!-- TODO Add dropdown on mobile -->
    <div class="dropdown">
      <ul class="flex gap-4 text-slate-500 font-bold">
        <li><a class="text-accent" href="">Home</a></li>
        <li><a href="">Donation</a></li>
        <li><a href="">Alumni of the year</a></li>
      </ul>
    </div>
  </header>

  <!-- Heading  -->
  <div class="text-white bg-black heading-container px-32 flex items-center justify-end bg-left-top bg-no-repeat bg-cover h-[35rem]">
    <div class="w-96 heading-text text-right space-y-2">
      <h2 class="text-5xl italic font-bold">"Let's be connected"</h2>
      <p class="text-2xl max-w-prose">
        Nobody is more bothered about an institution more than its alumni
      </p>
      <!-- CTA -->
      <button class="btn-primary bg-accent px-8 py-3 rounded-lg text-white font-bold">
        Sign In
      </button>
    </div>
  </div>
  <!-- Program and Events -->
  <section class="container mx-auto py-8">
    <h2 class="text-3xl font-neutral-800 font-bold">Programs and Events</h2>
    <!-- TODO configure for mobile design -->
    <div class="px-4 flex flex-col md:flex-row gap-4 p-8 justify-around">
      <div class="card w-64 mb-6 space-y-4">
        <div class="">
          <img class="object-cover" src="./assets/event-images/01.png" alt="" />
        </div>
        <div class="">
          <div class="flex flex-col sm:flex-row space-x-2">
            <p class="text-slate-800 font-bold">Homecoming</p>
            <span class="text-slate-500">November 22, 2021</span>
          </div>
        </div>
        <p class="text-slate-700">
          Let's now gather for the homecoming event for the alumni
        </p>
      </div>
      <!-- card 2 -->
      <div class="card w-64 mb-6 space-y-4">
        <div class="">
          <img class="object-cover" src="./assets/event-images/02.png" alt="" />
        </div>
        <div class="">
          <div class="flex flex-col sm:flex-row space-x-2">
            <p class="text-slate-800 font-bold">Alumni Night Out</p>
            <span class="text-slate-500">November 22, 2021</span>
          </div>
        </div>
        <p class="text-slate-700">
          Let's now gather for the homecoming event for the alumni
        </p>
      </div>
      <!-- card 3 -->

      <div class="card w-64 mb-6 space-y-4">
        <div class="">
          <img class="object-cover" src="./assets/event-images/03.png" alt="" />
        </div>
        <div class="">
          <div class="flex flex-col sm:flex-row space-x-2">
            <p class="text-slate-800 font-bold">FireFox</p>
            <span class="text-slate-500">January 05, 2023</span>
          </div>
        </div>
        <p class="text-slate-700">
          Let's now gather for the homecoming event for the alumni
        </p>
      </div>
    </div>
  </section>

  <!-- Community Section -->
  <section class="container mx-auto mb-12">
    <div class="flex flex-row justify-center">
      <img class="w-[30rem]" src="./assets/community.png" alt="" />
      <div class="space-x-20 space-y-8 text-right">
        <h2 class="font-bold text-3xl">BulSuan Community</h2>
        <p class="max-w-prose text-justify">
          No storm will make the Bulacan State University (BulSU) community
          falter, especially in this time where our brothers and sisters need
          help and care as different organizations of BulSU work hand-in-hand
          to extend their love to those in need.
        </p>
        <button class="btn-primary bg-accent px-8 py-3 rounded-lg text-white font-bold">
          View Community
        </button>
      </div>
    </div>
  </section>

  <!-- Donation Section -->
  <div class="bg-grayish py-20">
    <section class="container mx-auto">
      <div class="flex flex-row justify-around">
        <div>
          <img class="w-[30rem]" src="./assets/donation.png" alt="" />
        </div>
        <div class="text-center space-y-12">
          <h2 class="text-3xl font-bold text-white">Donations</h2>
          <p class="max-w-prose text-slate-300">
            Help us to reach more people not only our university student but
            also the one who's looking for help. Give a helping hand to
            provide better community, and better nation.You can give donation
            for your college to help them provide a better feature event for
            there student and also for other alumni. You can also donate for
            the university .
          </p>
          <p class="max-w-prose text-slate-300">
            You can give donation for your college to help them provide a
            better feature event for there student and also for other alumni.
            You can also donate for the university .
          </p>
        </div>
      </div>
    </section>
  </div>

  <!-- Calendar Section -->
  <section class="container mx-auto text-center space-y-8 py-8">
    <div class="text my-12">
      <h2 class="text-3xl font-light text-slate-500 text-center">
        Bulacan State University Alumni
        <span class="text-accent text-3xl">Calendar</span>
      </h2>
    </div>

    <div class="flex flex-row justify-around">
      <div class="flex flex-col items-center">
        <div class="border-accent border-4 w-32 h-32 rounded-full flex items-center justify-center flex-col">
          <div class="font-bold text-5xl text-slate-700">26</div>
          <div>May</div>
        </div>
        <p class="font-bold">Alumni Homecoming</p>
      </div>

      <div class="flex flex-col items-center">
        <div class="border-accent border-4 w-32 h-32 rounded-full flex items-center justify-center flex-col">
          <div class="font-bold text-5xl text-slate-700">3</div>
          <div>March</div>
        </div>
        <p class="font-bold">Fun Run</p>
      </div>
      <div class="flex flex-col items-center">
        <div class="border-accent border-4 w-32 h-32 rounded-full flex items-center justify-center flex-col">
          <div class="font-bold text-5xl text-slate-700">5</div>
          <div>June</div>
        </div>
        <p class="font-bold">CICT Week</p>
      </div>
    </div>

    <button class="btn-secondary bg-secondary px-8 py-3 rounded-lg text-white font-bold">
      View Calendar
    </button>
  </section>

  <?php
  require("./partials/footer.php")
  ?>

</body>

</html>