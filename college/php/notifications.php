<?php


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
 * @param string $action:
 *  - posted
 * - updated
 * - emailed
 * - event
 * - commented
 * - signin
 * - signout
 * @param string $details
 * @return bool
 * 
 */
function setNewNotification(mysqli $mysql_con, string $colAdmin, string $action, string $details): bool
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
 * Get recent notif of the college
 * 
 * @param mysqli $mysql_con
 * @param string $colCode
 * @return array
 * 
 */
function getRecentNotifications(mysqli $mysql_con, string $colAdmin)
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
