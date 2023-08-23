<!--community content -->
<section>

    <div id="community-tab" class="p-5">
        <!-- <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute top-3 left-3"></i>
            <input class="pl-8 w-1/2 rounded " type="text" id="communitySearch" placeholder="Search something...">
        </div> -->


    </div>

    <!--community content -->
    <div id="community-tab" class="p-5">
        <div class="flex">
            <div class="w-4/6">
                <!-- college -->
                <select id="communityCollege" class="w-1/2 p-2 my-5 outline-none">
                    <option value="" selected>College</option>
                    <?php
                    require_once '../php/connection.php';
                    // TODO redo this in order to add only courses in the college
                    $query = "SELECT * FROM `college`";
                    $result = mysqli_query($mysql_con, $query);
                    $rows = mysqli_num_rows($result);

                    if ($rows > 0) {
                        while ($data = mysqli_fetch_assoc($result)) {
                            $colCode = $data['colCode'];
                            $colName = $data['colname'];

                            echo '<option value="' . $colCode . '">' . $colName . '</option>';
                        }
                    } else echo '<option>No college available</option>';
                    ?>
                </select>

                <!-- report number -->
                <select id="communityReportFilter" class="outline-none w-1/3 p-2  my-5">
                    <option value="" selected>All</option>
                    <option value="Nudity">Nudity</option>
                    <option value="Violence">Violence</option>
                    <option value="Terrorism">Terrorism</option>
                    <option value="Hate Speech">Hate Speech</option>
                    <option value="False Information">False Information</option>
                    <option value="Suicide or Self-injury">Suicide or Self-injury</option>
                    <option value="Harassment">Harassment</option>
                </select>

                <div id="communityContainer" class="p-5 flex flex-col gap-3 no-scrollbar"></div>
                <p id="noPostMsgCommunity" class="text-blue-400 text-center hidden">No available post</p>
            </div>
            <!-- report graph -->
            <div class=" w-2/5 border-l border-gray-300">
                <p class="text-center font-bold text-xl">Report Graph</p>
                <canvas id="reportChart">

                </canvas>
            </div>
        </div>

    </div>


</section>


<script>
    $(document).ready(function() {
        $.getScript("community/postScript.js");
    });
</script>