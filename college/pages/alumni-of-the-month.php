<section class="container p-4">
    <h1 class="font-bold text-2xl">Alumni of the Month</h1>
    <p class="text-gray-400 font-bold text-sm">Make post for alumni of the month</p>

    <!-- Post Form for AOTM  -->
    <section class="relative my-6">
        <form action="post/to/server">
            <div class="form-control space-y-2 my-2">
                <label for="fullname" class="font-bold block ">Fullname</label>
                <input type="text" name="fullname" id="fullname" class="  input-text add-focus w-96 rounded-md">
            </div>
            <div class="form-control">
                <label for="quote" class="font-bold block">Add Quote</label>
                <input type="text" name="quote" id="quote" class="input-text add-focus w-96 rounded-md">
            </div>
            <div class="form-control">
                <label for="batch" class="font-bold block">Batch</label>
                <select type="text" name="batch" id="batch" class="input-text add-focus w-96 rounded-md">
                    <option value="2023">2023</option>
                    <option value="2023">2022</option>
                    <option value="2023">2021</option>
                    <option value="2023">2020</option>
                    <option value="2023">To be continued in the server</option>
                </select>
            </div>

            <!-- File Input -->
            <div class="form-control">
                <label for="quote" class="font-bold block">Image to be showcased</label>
                <input type="file" class="block w-full text-sm text-slate-500
                      file:m-4 file:p-2 file:border-0
      file:rounded-lg file:text-sm file:font-semibold file:bg-accent file:text-white  hover:file:bg-red-400
                " />
            </div>

            <div class="form-control space-y-2">
                <label for="social-link" class="font-bold block">Social Links</label>
                <div>

                    <i class="fa-solid fa-plus p-1  border-2 rounded-full border-blue-950"></i>
                    <span>Add Social Media Link</span>
                </div>
            </div>
            <div class="form-control space-y-2">
                <label for="description" class="font-bold block">Description</label>
                <textarea class="input-text rounded-lg" name="description" id="description" cols="70" rows="5" placeholder="Describe the person or provide other information you want to share to other people..."></textarea>
            </div>


            <div class="flex justify-end w-full">

                <button class="btn-primary bg-secondary py-4">Make A Post</button>
            </div>


        </form>
    </section>
</section>