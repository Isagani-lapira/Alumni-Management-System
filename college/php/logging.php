<?php





function logPostActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "posted";
    $details = " posted an event";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}
function logUpdateActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "updated";
    $details = " updated an event";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}
function logEmailedActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "emailed";
    $details = " added a new email";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}
function logEventActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "event";
    $details = " created an event";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}
function logCommentedActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "commented";
    $details = "added a new comment";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}
function logSigninActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "signin";
    $details = "signed in";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}
function logSignoutActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "signout";
    $details = "signed out";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}


// deleted an activity
function logDeleteActivity(mysqli $mysql_con, $colAdmin, $colCode)
{
    $action = "delete";
    $details = "deleted an event.";
    setNewActivity($mysql_con, $colAdmin, $action, $details);
}



/**
 * Get the latest nth activity
 * 
 * @param mysqli $mysql_con
 * @param int $limit
 * 
 */
function getNewActivityByLimit(mysqli $mysql_con, int $limit)
{
    // Initialize the statement
    $stmt = $mysql_con->stmt_init();

    $stmt->prepare('
        SELECT * FROM collegeadmin_log ORDER BY date_posted DESC LIMIT ?;
        ');

    // bind the parameters
    $stmt->bind_param(
        'i',
        $limit
    );

    try {
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);
        // iterate the results into an array
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $result;
    } catch (\Throwable $th) {
        //throw $th;
        echo  $th->getMessage();
    }
}

/***
 * Set new activity
 * 
 * @param mysqli $mysql_con
 * @param string $colAdmin
 * @param string $action
 * @param string $details
 * @return bool
 * 
 */
function setNewActivity(mysqli $mysql_con, $colAdmin, $action, $details): bool
{


    // Initialize the statement
    $stmt = $mysql_con->stmt_init();

    $stmt->prepare('INSERT INTO collegeadmin_log (colAdmin, action, details)
        VALUES (?, ?, ?)
    ');

    // bind the parameters
    $stmt->bind_param(
        'sss',
        $colAdmin,
        $action,
        $details
    );

    try {
        // execute the query
        return $stmt->execute();
    } catch (\Throwable $th) {
        //throw $th;
        echo  $th->getMessage();
        return false;
    }
}

function getTotalEvents(string $colCode)
{
}


/***
 * 
 * Get recent activity of the college
 * 
 * @param mysqli $mysql_con
 * @param string $colCode
 * @return array
 * 
 */
function getRecentCollegeAcivity(mysqli $mysql_con, string $colAdmin)
{
    // Initialize the statement
    $stmt = $mysql_con->stmt_init();

    $stmt->prepare('
    SELECT * FROM collegeadmin_log WHERE colAdmin = ? ORDER BY timestamp DESC LIMIT 5;
    ');

    // bind the parameters
    $stmt->bind_param(
        's',
        $colAdmin
    );

    try {
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);

        // get all the results
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $result;
    } catch (\Throwable $th) {
        //throw $th;
        echo  $th->getMessage();
    }
}

/**
 * TODO
 */
// Get the recent activity of specific college admin
function getRecentAdminActivity(mysqli $mysql_con, $colAdmin)
{
    // Initialize the statement
    $stmt = $mysql_con->stmt_init();

    $stmt->prepare('
    SELECT * FROM collegeadmin_log WHERE colAdmin = ? ORDER BY date_posted DESC LIMIT 5;
    ');

    // bind the parameters
    $stmt->bind_param(
        's',
        $colAdmin
    );

    try {
        // execute the query
        $stmt->execute();
        // gets the myql_result. Similar result to mysqli_query
        $result = $stmt->get_result();
        $num_row = mysqli_num_rows($result);
    } catch (\Throwable $th) {
        //throw $th;
        echo  $th->getMessage();
    }
}
