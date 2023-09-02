<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet" />
    <link href="../style/style.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <title>Document</title>
</head>

<body>
    <nav class="p-3 border-b border-accent">
        <section class="logo flex gap-2">
            <img src="../assets/bulsu-logo.png" class="h-20 w-20">
            <input id="formTitle" class="font-bold text-2xl outline-none text-greyish_black" type="text"
                value="Untitled Graduate Tracer">
        </section>
    </nav>
    <main>
        <section class="subbar w-full flex justify-center mb-5">
            <span id="subBar"
                class="text-white bg-accent rounded-b-lg w-1/4 text-center py-3 text-lg font-bold">Question
                Categorization</span>
        </section>
        <p class="text-gray-500 italic text-center w-2/5 mx-auto">This section allows you to set a categorization for
            different sets of question to be easily distinguish by the one answering</p>

        <section id="questionnaire" class="w-2/5 block mx-auto my-5">

            <div>
                <div id="categoryContainer"
                    class=" rounded-lg border-t-8 border-accent p-5 justify-between center-shadow">
                    <div id="categoryWrapper" class="flex flex-col gap-2">

                        <div class="mb-2">
                            <input class="border-b border-gray-400 py-2 px-2 outline-none w-full categoryField"
                                type="text" placeholder="Add category">
                        </div>

                    </div>
                    <button id="btnAddCat"
                        class="text-white px-4 py-2 bg-blue-400 hover:bg-blue-500 my-3 rounded-md">Add
                        category</button>
                </div>
            </div>

            <div id="categorySection"></div>

        </section>
        <footer id="paginationWrapper" class="w-2/5 flex justify-end mx-auto my-3 gap-2">
            <button class="text-gray-500 hover:bg-gray-300 py-2 px-4 rounded-md">Cancel</button>
            <button id="nextpage"
                class="text-white bg-accent px-4 py-2 font-bold rounded-md hover:bg-darkAccent">Next</button>
        </footer>
    </main>


    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="../js/tracer.js"></script>
</body>

</html>