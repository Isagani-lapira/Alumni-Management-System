<?php session_start();
require_once '../../config.php';
require_once "../php/connection.php";

require_once SITE_ROOT . "/PHP_process/TracerForm.php";

$tracer = new TracerForm($mysql_con);
$deployments = $tracer->get_deployments();

var_dump($deployments[0]);


?>


<section class=" mx-auto   p-5 lg:mx-8 overflow-auto">
    <input type="hidden" id="colCode-hidden" name="colCode" value="<?= $_SESSION['colCode'] ?>">

    <h1 class="text-xl font-extrabold">Alumni Tracer Record</h1>
    <p class="text-grayish">See the records of the Alumni </p>

    <div class="flex flex-wrap gap-4">

        <div class="form-container">
            <label class="block font-bold" for="select-deployment-filter">Deployment Date: </label>
            <select name="type" id="select-deployment-filter" class=" form-select rounded ">

                <?php foreach ($deployments as $key => $value) : ?>
                    <option value="<?= $value['tracer_deployID'] ?>"><?= $value['year_created'] ?></option>
                <?php endforeach; ?>


            </select>
        </div>


    </div>


    <!-- Table Container -->
    <div id="table-container">

        <div class="dt-container">
            <!-- Start Record Table -->
            <table class="  table-auto w-full mt-10  rounded-t-md center-shadow daisy-table daisy-table-zebra" id="tracer-table">
                <thead class="">
                    <tr class="bg-accent text-white  rounded-tl-md">
                        <th class="rounded-tl-lg">NAME</th>
                        <th>BATCH</th>
                        <th>COURSE</th>
                        <th>STATUS</th>
                        <th>VIEW RECORD</th>
                        <!-- <th>DETAILS</th> -->
                    </tr>
                </thead>
                <!-- To be filled later -->
                <tbody id="" class="text-sm">
                </tbody>
            </table>
        </div>


    </div>



    <!-- view modal -->

    <input type="checkbox" id="view-person-modal" class="daisy-modal-toggle">
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-5xl ">
            <!-- Exit -->
            <form method="dialog">
                <label for="view-person-modal" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
            </form>
            <!-- End Exit Form -->
            <h2 class="font-bold text-accent text-xl py-4">View Record Details</h2>

            <div id="form-details-container">

            </div>








        </div>
        <label class="daisy-modal-backdrop" for="view-person-modal">Close</label>
    </div>

    </div>
</section>










</section>



<script type="module" src="./tracer-record/tracer-record.js"></script>