<?php

class AlumniOfTheMonth
{
    private mysqli $conn;
    private string $colCode;

    public function __construct(mysqli $conn = null, string $colCode)
    {
        $this->conn = $conn;
        $this->colCode = $colCode;
    }

    public function getUserCountByDate($date): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `alumni_of_the_month` 
        WHERE colCode = ? dateCreated = ? ;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $this->colCode);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // count 
        $count = '';

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $count = $record['total'];
            }
        } else {
            return 0;
        }


        return $count;
    }

    public function setNewAlumniOfTheMonth(string $studentId, array $details, string $post_id = ''): array
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        // check if there is a post_id
        if ($post_id !== '') {
            // $stmt = $this->conn->prepare('INSERT INTO alumni_of_the_month (studentNo,personID, quote, cover_img ,colCode, date_assigned ,post_id)
            // VALUES (?,?,?,?,?,CURDATE(),?);');
            // // $stmt = $this->conn->prepare('INSERT INTO alumni_of_the_month (studentNo,personID, quote, cover_img ,colCode, date_assigned,description ,post_id)
            // // VALUES (?,?,?,?,?,CURDATE(),?,?);');

            // // *  Binds the variable to the '?', prevents sql injection
            // try {
            //     $stmt->bind_param('sssssss',  $studentId, $details['personID'], $details['quote'], $details['cover-img'], $this->colCode, $post_id);
            //     // $stmt->bind_param('sssssss',  $studentId, $details['personID'], $details['quote'], $details['cover-img'], $this->colCode, $details['description'], $post_id);
            //     // execute the query
            //     if ($stmt->execute()) {
            //         $lastInsertedID = mysqli_insert_id($this->conn);

            //         return [
            //             'status' => true,
            //             'id' => $lastInsertedID
            //         ];
            //     } else {
            //         return [
            //             'status' => false,
            //             'id' => ''
            //         ];
            //     }
            // } catch (\Throwable $th) {
            //     throw $th;
            // }
        } else {
            $stmt = $this->conn->prepare('INSERT INTO alumni_of_the_month (studentNo,personID, quote, cover_img ,colCode, date_assigned )
            VALUES (?,?,?,?,?,CURDATE());');
            // $stmt = $this->conn->prepare('INSERT INTO alumni_of_the_month (studentNo,personID, quote, cover_img ,colCode, date_assigned,description )
            // VALUES (?,?,?,?,?,CURDATE(),?);');

            // *  Binds the variable to the '?', prevents sql injection
            try {
                $stmt->bind_param('sssss',  $studentId, $details['personID'], $details['quote'], $details['cover-img'], $this->colCode);
                // $stmt->bind_param('ssssss',  $studentId, $details['personID'], $details['quote'], $details['cover-img'], $this->colCode, $details['description']);
                // execute the query
                if ($stmt->execute()) {
                    $lastInsertedID = mysqli_insert_id($this->conn);

                    return [
                        'status' => true,
                        'id' => $lastInsertedID
                    ];
                } else {
                    return [
                        'status' => false,
                        'id' => ''
                    ];
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
    public function deleteAlumniOfTheMonth(string $aotmID): bool
    {

        // deletes all testimonials and achievements of the alumni of the month
        $this->removeAllAchievementsOfAOTM($aotmID);
        $this->removeAllTestimonialsOfAOTM($aotmID);

        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('DELETE FROM alumni_of_the_month WHERE AOMID = ?;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $aotmID);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAchievement(string $achievementID, array $data): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('UPDATE achievement SET achievement = ? , description = ? , date = ? WHERE achievementID = ?;');

        $stmt->bind_param('ssss', $data['achievement'], $data['description'], $data['date'], $achievementID);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getTestimonialById(string $testimonialID): array
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT * FROM testimonials WHERE testimonialID = ?;');

        $stmt->bind_param('s', $testimonialID);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
                $record['profile_img'] = base64_encode($record['profile_img']);
                $resultArray[] = $record;
            }
        } else {
        }

        return $resultArray;
    }

    public function getAchievementById(string $achievementID): array
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('SELECT * FROM achievement WHERE achievementID = ?;');

        $stmt->bind_param('s', $achievementID);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $resultArray[] = $record;
            }
        } else {
        }

        return $resultArray;
    }


    public function updateTestimonial(string $testimonialID, array $data): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        // check if the profile image is empty
        if ($data['profile_img'] !== '') {
            $stmt = $this->conn->prepare('UPDATE testimonials SET message = ? , date = ? , emailAddress = ? , person_name = ? , relationship = ? , companyName = ? , position = ? , profile_img = ? WHERE testimonialID = ?;');

            $stmt->bind_param('sssssssss', $data['message'], $data['date'], $data['emailAddress'], $data['person_name'], $data['relationship'], $data['companyName'], $data['position'], $data['profile_img'], $testimonialID);
        } else {
            $stmt = $this->conn->prepare('UPDATE testimonials SET message = ? , date = ? , emailAddress = ? , person_name = ? , relationship = ? , companyName = ? , position = ? WHERE testimonialID = ?;');

            $stmt->bind_param('ssssssss', $data['message'], $data['date'], $data['emailAddress'], $data['person_name'], $data['relationship'], $data['companyName'], $data['position'], $testimonialID);
        }

        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function removeAllAchievementsOfAOTM(string $aotmID): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('DELETE FROM achievement WHERE AOMID = ?;');

        $stmt->bind_param('s', $aotmID);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function removeOneAchievementByAchievementID(string $id): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('DELETE FROM achievement WHERE achievementID = ?;');

        $stmt->bind_param('s', $id);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function removeOneTestimonialtByTestimonialID(string $id): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('DELETE FROM testimonials WHERE testimonialID = ?;');

        $stmt->bind_param('s', $id);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function removeAllTestimonialsOfAOTM(string $aotmID): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('DELETE FROM testimonials WHERE AOMID = ?;');

        $stmt->bind_param('s', $aotmID);
        // execute the query

        // check if the query is successful
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function setNewTestimonial($data)
    {
        $stmt = $this->conn->stmt_init();


        $stmt = $this->conn->prepare('INSERT INTO testimonials (
            testimonialID,
            AOMID,
            message,
            date,
            emailAddress,
            person_name,
            relationship,
            companyName,
            position,
            profile_img

        )
        VALUES (?,?,?,?,?,?,?,?,?,?);');

        try {
            $stmt->bind_param(
                'ssssssssss',
                $data['id'],
                $data['aotmID'],
                $data['message'],
                $data['date'],
                $data['emailAddress'],
                $data['person_name'],
                $data['relationship'],
                $data['companyName'],
                $data['position'],
                $data['profile_img']


            );
            // execute the query
            if ($stmt->execute()) {
                $lastInsertedID = mysqli_insert_id($this->conn);

                return [
                    'status' => true,
                    'id' => $lastInsertedID
                ];
            } else {
                return [
                    'status' => false,
                    'id' => ''
                ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function setNewAchievement($achievementInformation)
    {
        $stmt = $this->conn->stmt_init();

        $stmt = $this->conn->prepare('INSERT INTO achievement (achievementID ,AOMID, achievement, description, date)
        VALUES (?,?,?,?,?);');

        // *  Binds the variable to the '?', prevents sql injection
        try {
            $stmt->bind_param('sssss',  $achievementInformation['id'], $achievementInformation['aotmID'], $achievementInformation['achievement'], $achievementInformation['description'], $achievementInformation['date']);
            // execute the query
            if ($stmt->execute()) {
                $lastInsertedID = mysqli_insert_id($this->conn);

                return [
                    'status' => true,
                    'id' => $lastInsertedID
                ];
            } else {
                return [
                    'status' => false,
                    'id' => ''
                ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateExistingAlumniOfTheMonth($aotmID, array $details): bool
    {
        // Initialize the statement
        $stmt = $this->conn->stmt_init();

        // // if there is a description
        // if ($details['description'] !== '') {
        //     // check if the cover image is empty
        //     if ($details['cover-img'] !== '') {
        //         $stmt = $this->conn->prepare('UPDATE alumni_of_the_month SET studentNo = ? , personID = ? ,  quote = ?, cover_img = ? , description = ? WHERE AOMID = ?;');

        //         $stmt->bind_param('ssssss', $details['studentNo'], $details['personID'], $details['quote'], $details['cover-img'], $details['description'], $aotmID);
        //     } else {
        //         $stmt = $this->conn->prepare('UPDATE alumni_of_the_month SET studentNo = ? , personID = ? , quote = ? , description = ? WHERE AOMID = ?;');

        //         $stmt->bind_param('sssss', $details['studentNo'], $details['personID'], $details['quote'], $details['description'], $aotmID);
        //     }
        // }

        if ($details['cover-img'] !== '') {
            $stmt = $this->conn->prepare('UPDATE alumni_of_the_month SET studentNo = ? , personID = ? ,  quote = ?, cover_img = ?,  WHERE AOMID = ?;');

            $stmt->bind_param('sssss', $details['studentNo'], $details['personID'], $details['quote'], $details['cover-img'],  $aotmID);
        } else {
            $stmt = $this->conn->prepare('UPDATE alumni_of_the_month SET studentNo = ? , personID = ? , quote = ?  WHERE AOMID = ?;');

            $stmt->bind_param('ssss', $details['studentNo'], $details['personID'], $details['quote'],  $aotmID);
        }

        // execute the query
        $stmt->execute();

        return true;
    }


    public function getTotalCount(): int
    {

        // Initialize the statement
        $stmt = $this->conn->stmt_init();



        $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM `alumni_of_the_month` WHERE colCode = ?;');

        // *  Binds the variable to the '?', prevents sql injection
        $stmt->bind_param('s', $this->colCode);
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // count 
        $count = '';

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                $count = $record['total'];
            }
        } else {
            return 0;
        }


        return $count;
    }


    public function getFullDetailById(string $id): array
    {

        //get the person ID of user
        $query = 'SELECT * FROM `alumni_of_the_month` WHERE studentNo = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();

        $result = $stmt->get_result();

        if (
            $result->num_rows
        ) {

            return $result->fetch_assoc();
        }

        return [];
    }


    function getAllLatest(int $offset = 0): array
    {
        // TODO use a more sensible query
        $stmt = $this->conn->prepare('SELECT alumni_of_the_month.*, 
        CONCAT(fName, " ", lName) AS fullname  FROM `alumni_of_the_month`
            INNER JOIN `alumni` on studNo = studentNo
            INNER JOIN `person` on person.personID = alumni.personID
              WHERE alumni_of_the_month.colCode = ?
              ORDER BY date_assigned DESC
              LIMIT  10  OFFSET  ? ;');
        $stmt->bind_param('si',  $this->colCode, $offset);

        try {
            // execute the query
            $stmt->execute();
            // gets the myql_result. Similar result to mysqli_query
            $result = $stmt->get_result();
            $num_row = mysqli_num_rows($result);
        } catch (\Throwable $th) {
            throw $th;
        }



        // holds every row in the query
        $resultArray = array();

        if ($result && $num_row > 0) {
            // Gets every row in the query
            while ($record = mysqli_fetch_assoc($result)) {
                // ! README ALWAYS USE base64_encode() when sending image to client. 2 Hours wasted because of this. 
                // $record['profile_img'] = base64_encode($record['profile_img']);
                $record['cover_img'] = base64_encode($record['cover_img']);
                $resultArray[] = $record;
            }
        } else {
        }

        return $resultArray;
    }
}
