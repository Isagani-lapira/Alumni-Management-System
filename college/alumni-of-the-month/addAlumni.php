<?php
session_start();

require "../model/AlumniOfTheMonth.php";
require "../php/connection.php";
require "../php/logging.php";
require "../php/notifications.php";
require_once '../../PHP_process/postTB.php';




//    check if college admin is logged in
if ($_SESSION['accountType'] !== 'ColAdmin') {
    // TODO redirect to error page.
    header("Location: ../index.php");
    exit();
}

// check if session admin is set
if (!isset($_SESSION['college_admin']) && !isset($_SESSION['adminID'])) {
    // TODO redirect to error page.
    header("Location: ../index.php");
    exit();
}



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get session id
    $adminID = $_SESSION['adminID'];
    $colCode = $_SESSION['colCode'];

    // var_dump($_POST);
    // echo isset($_POST['achievement']) ? 'there' : 'not there';
    // echo isset($_POST['testimonial']) ? 0 : 1;
    // die();

    // Create an instance of the model class
    $alumni = new AlumniOfTheMonth($mysql_con, $colCode);

    if (isset($_POST['action'])) {


        $action = $_POST['action'];


        if ($action === 'delete-one-testimonial') {
            $testimonialID = $_POST['testimonialID'];



            // set new achievement
            $result = $alumni->removeOneTestimonialtByTestimonialID($testimonialID);
            header("Content-Type: application/json; charset=UTF-8");
            if ($result === TRUE) {

                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Achievement deleted successfully',
                        'status' => true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Achievement not deleted',
                        'status' => false
                    )
                );
            };
        } else if ($action === 'edit-one-testimonial') {
            $tID = $_POST['testimony-id'];
            $person_name = $_POST['filled-person_name'];
            $relationship = $_POST['filled-relationship'];
            $emailAddress = $_POST['filled-emailAddress'];
            $companyName = $_POST['filled-companyName'];
            $position = $_POST['filled-position'];
            $message = $_POST['filled-message'];
            $date = $_POST['filled-date'];


            // get the 'profile_img' from post and convert to blob
            $profileImg = '';
            if (isset($_FILES['filled-profile_img']) && $_FILES['filled-profile_img']['size'] > 0) {
                $profileImgTmpName =  $_FILES['filled-profile_img']['tmp_name'];
                $profileImg = file_get_contents($profileImgTmpName);
            }



            // make array of testimonial information
            $info = array(
                'id' => $tID,
                'person_name' => $person_name,
                'relationship' => $relationship,
                'emailAddress' => $emailAddress,
                'companyName' => $companyName,
                'position' => $position,
                'message' => $message,
                'date' => $date,
                'profile_img' => $profileImg
            );

            // set new testimonial
            $result = $alumni->updateTestimonial($tID, $info);
            header("Content-Type: application/json; charset=UTF-8");
            if ($result === TRUE) {

                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Testimonial updated successfully',
                        'status' => true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Testimonial not updated',
                        'status' => false
                    )
                );
            };
        }

        if ($action === 'delete-one-achievement') {


            $achievementID = $_POST['achievementID'];



            // set new achievement
            $result = $alumni->removeOneAchievementByAchievementID($achievementID);
            header("Content-Type: application/json; charset=UTF-8");
            if ($result === TRUE) {

                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Achievement deleted successfully',
                        'status' => true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Achievement not deleted',
                        'status' => false
                    )
                );
            };
        } else if ($action === 'edit-one-achievement') {

            // $title = $_POST['title'];
            $achievement = $_POST['achievement'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $achievementID = $_POST['achievementID'];

            // make array of achievement information
            $data = array(
                'achievement' => $achievement,
                'description' => $description,
                'date' => $date,
                'achievementID' => $achievementID
            );

            // set new achievement
            $result = $alumni->updateAchievement($achievementID, $data);
            header("Content-Type: application/json; charset=UTF-8");
            if ($result === TRUE) {

                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Achievement updated successfully',
                        'status' => true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Achievement not updated',
                        'status' => false
                    )
                );
            };
        } else if ($action === 'edit-achievement') {

            $aID = $_POST['aID'];
            $aTitle = $_POST['aTitle'];
            $aDescription = $_POST['aDescription'];
            $aDate = $_POST['aDate'];
            $achievementID = $_POST['aotmID'];

            // make array of achievement information
            $achievementInformation = array(
                'aID' => $aID,
                'aTitle' => $aTitle,
                'aDescription' => $aDescription,
                'aDate' => $aDate,
                'aotmID' => $aotmID
            );

            // set new achievement
            $result = $alumni->updateAchievement($achievementID, $achievementInformation);
            header("Content-Type: application/json; charset=UTF-8");
            if ($result === TRUE) {

                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Achievement updated successfully',
                        'status' => true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Achievement not updated',
                        'status' => false
                    )
                );
            };
        } else if ($action === 'edit') {



            $personID = $_POST['personID'];
            $quote = $_POST['quote'];
            $aotmID = $_POST['aotm-id'];
            $description = $_POST['description'];
            $id = $_POST['id'];
            $studentNo = $_POST['studentNo'];

            $cover_img = '';
            // check if cover image is available
            if (isset($_FILES['cover-image']) && $_FILES['cover-image']['size'] > 0) {
                $cover_imgTmpName =  $_FILES['cover-image']['tmp_name'];
                $cover_img = file_get_contents($cover_imgTmpName);
            }

            // compile into array
            $alumniInformation = array(
                'studentNo' => $studentNo,
                'personID' => $personID,
                'quote' => $quote,
                'cover-img' => $cover_img,
                'description' => $description,
                'aotmID' => $aotmID

            );
            // set update alumni



            try {

                $result = $alumni->updateExistingAlumniOfTheMonth($aotmID, $alumniInformation,);
                header("Content-Type: application/json; charset=UTF-8");
                if ($result === TRUE) {
                    $action = "posted";
                    $details = "posted a new Alumni of the Month";
                    setNewActivity($mysql_con, $_SESSION['adminID'], $action, $details);
                    echo json_encode(
                        array(
                            'response' => 'Successful',
                            'message' => 'Alumni updated successfully',
                            'status' => true
                        )
                    );
                } else {
                    echo json_encode(
                        array(
                            'response' => 'Unsuccessful',
                            'message' => 'Alumni not updated',
                            'status' => false
                        )
                    );
                };
                // if there is achievement on the post
                if (isset($_POST['achievement'])) {

                    // get the a-title, a-description, a-date and generate an id for each achievement
                    // loop through

                    $aTitle = $_POST['a-title'];
                    // check if it is an array
                    if (is_array($aTitle)) {
                        // loop through
                        try {
                            foreach ($aTitle as $key => $value) {
                                $aTitle = $_POST['a-title'][$key];
                                $aDescription = $_POST['a-description'][$key];
                                $aDate = $_POST['a-date'][$key];
                                $aID = uniqid('aotm-');

                                // make array of achievement information
                                $achievementInformation = array(
                                    'id' => $aID,
                                    'achievement' => $aTitle,
                                    'description' => $aDescription,
                                    'date' => $aDate,
                                    'aotmID' => $aotmID
                                );

                                // set new achievement
                                $result = $alumni->setNewAchievement($achievementInformation);
                            }
                        } catch (\Throwable $th) {
                            throw $th;
                        }
                    } else {
                        $aTitle = $_POST['a-title'];
                        $aDescription = $_POST['a-description'];
                        $aDate = $_POST['a-date'];
                        $aID = uniqid('aotm-');

                        // make array of achievement information
                        $achievementInformation = array(
                            'aID' => $aID,
                            'aTitle' => $aTitle,
                            'aDescription' => $aDescription,
                            'aDate' => $aDate,
                            'aotmID' => $aotmID
                        );

                        // set new achievement
                        $result = $alumni->setNewAchievement($achievementInformation);
                    }
                }


                // if there is testimonials on the post
                if (isset($_POST['testimonial'])) {

                    /**
                     * " ["person_name"]=> array(1) { [0]=> string(4) "asdf" } 
                     * ["relationship"]=> array(1) { [0]=> string(4) "dasf" } 
                     * ["emailAddress"]=> array(1) { [0]=> string(14) "sdfg@gmail.com" } 
                     * ["companyName"]=> array(1) { [0]=> string(5) "afsdf" } 
                     * ["position"]=> array(1) { [0]=> string(5) "fdasf" }
                     *  ["message"]=> array(1) { [0]=> string(4) "asdf" } 
                     * ["date"]=> array(1) { [0]=> string(10) "2222-12-03" } 
                     * ["testimonial"]=> string(0) "" } 
                     */

                    // get the a-title, a-description, a-date and generate an id for each achievement
                    // loop through

                    $item = $_POST['person_name'];
                    // check if it is an array
                    if (is_array($item)) {
                        // loop through
                        try {
                            foreach ($item as $key => $value) {
                                $fullname = $_POST['person_name'][$key];
                                $relationship = $_POST['relationship'][$key];
                                $emailAddress = $_POST['emailAddress'][$key];
                                $companyName = $_POST['companyName'][$key];
                                $position = $_POST['position'][$key];
                                $message = $_POST['message'][$key];
                                $date = $_POST['date'][$key];
                                $tID = uniqid('aotm-');

                                // get the 'profile_img' from post and convert to blob
                                $profileImgTmpName =  $_FILES['profile_img']['tmp_name'][$key];
                                $profileImg = file_get_contents($profileImgTmpName);


                                // make array of achievement information
                                $info = array(
                                    'id' => $tID,
                                    'person_name' => $fullname,
                                    'relationship' => $relationship,
                                    'emailAddress' => $emailAddress,
                                    'companyName' => $companyName,
                                    'position' => $position,
                                    'message' => $message,
                                    'date' => $date,
                                    'profile_img' => $profileImg,
                                    'aotmID' => $aotmID
                                );

                                // set new achievement
                                $result = $alumni->setNewTestimonial($info);
                            }
                        } catch (\Throwable $th) {
                            throw $th;
                        }
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Alumni not updated',
                        'status' => false,
                        'error' => $th->getMessage()
                    )
                );
            }
        } else if ($_POST['action'] == 'delete' && isset($_POST['aotm-id'])) {
            $aotmID = $_POST['aotm-id'];
            $result = $alumni->deleteAlumniOfTheMonth($aotmID);
            header("Content-Type: application/json; charset=UTF-8");
            if ($result === TRUE) {
                $action = "deleted";
                $details = "Deleted a record of Alumni of the Month";
                setNewActivity($mysql_con, $_SESSION['adminID'], $action, $details);
                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Alumni deleted successfully',
                        'status' => true
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Alumni not deleted',
                        'status' => false
                    )
                );
            };
        }
    } else {

        // Get the form data;
        $id = $_POST['studentNo'];
        $personID = $_POST['personID'];
        $quote = $_POST['quote'];
        $description = $_POST['description'];
        // image data
        // $profile_img = $_FILES['profile-image'];
        $cover_img = $_FILES['cover-image'];

        // Create an instance of the model class
        $alumni = new AlumniOfTheMonth($mysql_con, $colCode);

        // // Check if image file is larger than 2MB
        // if ($profile_img["size"] > 2000000 && $cover_img["size"] > 2000000) {
        //     echo "Sorry, your file is too large. Make it 2mb or less";
        // }


        // // check if image file is jpg
        // $imageMimeType = $profile_img["type"];
        // $image2MimeType = $cover_img["type"];
        // if ($imageMimeType != "image/jpg" && $imageMimeType != "image/jpeg") {

        //     echo "Sorry, only JPG files are allowed.";
        //     die();
        // }


        // Image data
        // $profileImgTmpName =  $_FILES['profile-image']['tmp_name'];
        // $profileImg = file_get_contents($profileImgTmpName);

        $cover_imgTmpName =  $_FILES['cover-image']['tmp_name'];
        $cover_img = file_get_contents($cover_imgTmpName);



        // make array of alumni information
        $alumniInformation = array(
            'studentNo' => $id,
            'personID' => $personID,
            'quote' => $quote,
            // 'emailAdd' => $emailAdd,
            // 'facebookUN' => $facebookUN,
            // 'linkedINUN' => $linkedINUN,
            // 'instagramUN' => $instagramUN,
            // 'profile-img' => $profileImg,
            'cover-img' => $cover_img,
            'description' => $description
        );



        // var_dump($_POST);
        // die();

        try {
            // set new alumni
            $result = $alumni->setNewAlumniOfTheMonth($id, $alumniInformation,);
            // get the id from the result
            $aotmID = $result['id'];
            // post it to the community
            $post = new PostData();
            $random = rand(0, 4000);
            $postID = uniqid() . '-' . $random;

            $post->createAOMPost(
                $aotmID,
                $_SESSION['username'],
                $postID,
                $colCode,
                $mysql_con
            );

            // add new notification
            $type_notif = 'aom';
            setNewNotification($mysql_con, $postID, $_SESSION['username'], $type_notif);



            header("Content-Type: application/json; charset=UTF-8");

            // if there is achievement on the post
            if (isset($_POST['achievement'])) {

                // get the a-title, a-description, a-date and generate an id for each achievement
                // loop through

                $aTitle = $_POST['a-title'];
                // check if it is an array
                if (is_array($aTitle)) {
                    // loop through
                    try {
                        foreach ($aTitle as $key => $value) {
                            $aTitle = $_POST['a-title'][$key];
                            $aDescription = $_POST['a-description'][$key];
                            $aDate = $_POST['a-date'][$key];
                            $aID = uniqid('aotm-');

                            // make array of achievement information
                            $achievementInformation = array(
                                'id' => $aID,
                                'achievement' => $aTitle,
                                'description' => $aDescription,
                                'date' => $aDate,
                                'aotmID' => $aotmID
                            );

                            // set new achievement
                            $result = $alumni->setNewAchievement($achievementInformation);
                        }
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                } else {
                    $aTitle = $_POST['a-title'];
                    $aDescription = $_POST['a-description'];
                    $aDate = $_POST['a-date'];
                    $aID = uniqid('aotm-');

                    // make array of achievement information
                    $achievementInformation = array(
                        'aID' => $aID,
                        'aTitle' => $aTitle,
                        'aDescription' => $aDescription,
                        'aDate' => $aDate,
                        'aotmID' => $aotmID
                    );

                    // set new achievement
                    $result = $alumni->setNewAchievement($achievementInformation);
                }
            }


            // if there is testimonials on the post
            if (isset($_POST['testimonial'])) {

                /**
                 * " ["person_name"]=> array(1) { [0]=> string(4) "asdf" } 
                 * ["relationship"]=> array(1) { [0]=> string(4) "dasf" } 
                 * ["emailAddress"]=> array(1) { [0]=> string(14) "sdfg@gmail.com" } 
                 * ["companyName"]=> array(1) { [0]=> string(5) "afsdf" } 
                 * ["position"]=> array(1) { [0]=> string(5) "fdasf" }
                 *  ["message"]=> array(1) { [0]=> string(4) "asdf" } 
                 * ["date"]=> array(1) { [0]=> string(10) "2222-12-03" } 
                 * ["testimonial"]=> string(0) "" } 
                 */

                // get the a-title, a-description, a-date and generate an id for each achievement
                // loop through

                $item = $_POST['person_name'];
                // check if it is an array
                if (is_array($item)) {
                    // loop through
                    try {
                        foreach ($item as $key => $value) {
                            $fullname = $_POST['person_name'][$key];
                            $relationship = $_POST['relationship'][$key];
                            $emailAddress = $_POST['emailAddress'][$key];
                            $companyName = $_POST['companyName'][$key];
                            $position = $_POST['position'][$key];
                            $message = $_POST['message'][$key];
                            $date = $_POST['date'][$key];
                            $tID = uniqid('aotm-');

                            // get the 'profile_img' from post and convert to blob
                            $profileImgTmpName =  $_FILES['profile_img']['tmp_name'][$key];
                            $profileImg = file_get_contents($profileImgTmpName);


                            // make array of achievement information
                            $info = array(
                                'id' => $tID,
                                'person_name' => $fullname,
                                'relationship' => $relationship,
                                'emailAddress' => $emailAddress,
                                'companyName' => $companyName,
                                'position' => $position,
                                'message' => $message,
                                'date' => $date,
                                'profile_img' => $profileImg,
                                'aotmID' => $aotmID
                            );

                            // set new achievement
                            $result = $alumni->setNewTestimonial($info);
                        }
                    } catch (\Throwable $th) {
                        throw $th;
                    }
                }
            }




            if ($aotmID !== '' &&  $result['status'] === TRUE) {
                $action = "posted";
                $details = "posted a new Alumni of the Month";
                setNewActivity($mysql_con, $_SESSION['adminID'], $action, $details);
                echo json_encode(
                    array(
                        'response' => 'Successful',
                        'message' => 'Alumni added successfully'
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'response' => 'Unsuccessful',
                        'message' => 'Alumni not added'
                    )
                );
            };
        } catch (\Throwable $th) {
            throw $th;
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // TODO redirect to error page.
    echo "GET request not allowed";
    // time for 5 seconds and redirect to index page
    header("refresh:5;url=../index.php");
    exit();
}
