<!--This file is built on and modified from the starter file provided at
https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/php-setup.html
-->

<?php

    $success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = NULL; // edit the login credentials in connectToDB()
    $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

// ------------------ START OF SYSTEM FUNCTIONS --------------------

    function debugAlertMessage($message) {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo "<br>running ".$cmdstr."<br>";
        global $db_conn, $success;

        $statement = OCIParse($db_conn, $cmdstr);
        //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
            echo htmlentities($e['message']);
            $success = False;
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }

        return $statement;
    }

    function executeBoundSQL($cmdstr, $list) {
        /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
    In this case you don't need to create the statement several times. Bound variables cause a statement to only be
    parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
    See the sample code below for how this function is used */

        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr);

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn);
            echo htmlentities($e['message']);
            $success = False;
        }

        foreach ($list as $tuple) {
            foreach ($tuple as $bind => $val) {
                //echo $val;
                //echo "<br>".$bind."<br>";
                OCIBindByName($statement, $bind, $val);
                unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                echo htmlentities($e['message']);
                echo "<br>";
                $success = False;
            }
        }
    }

    function connectToDB() {
        global $db_conn;

        // Your username is ora_(CWL_ID) and the password is a(student number). For example,
        // ora_platypus is the username and a12345678 is the password.
        
        // $db_conn = OCILogon("ora_seandhan", "a38290656", "dbhost.students.cs.ubc.ca:1522/stu");
//        $db_conn = OCILogon("ora_jwnl92", "a25438789", "dbhost.students.cs.ubc.ca:1522/stu");
        $db_conn = OCILogon("ora_mmai01", "a26575373", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            debugAlertMessage("Database is Connected");
            return true;
        } else {
            debugAlertMessage("Cannot connect to Database");
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
            return false;
        }
    }

    function disconnectFromDB() {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }
// ------------------ START OF HOME FUNCTIONS --------------------

    function showTablesRequest() {
        global $db_conn;
        global $success;

        $table = $_GET['table'];
        $attributes = $_GET['attributes'];

        $result = executePlainSQL("SELECT " . implode(", ", $attributes) . " FROM " . $table);

        if (!$success) {
            echo "<br> Query Failed.";
        } else {
            echo "Retrieved data from table " . $table . ":<br>";
            echo "<table>";
            echo "<tr>";
            foreach ($attributes as $attribute) {
                echo "<th>" . $attribute . "</th>";
            }
            echo "</tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr>";
                foreach ($attributes as $attribute) {
                    echo "<td>" . $row[$attribute] . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
        }
    }

// ------------------ START OF USERS FUNCTIONS --------------------

    function insertUserRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['insPhone'],
            ":bind2" => $_POST['insName'],
            ":bind3" => $_POST['insWeight'],
            ":bind4" => $_POST['insHeight']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("INSERT INTO Users VALUES (:bind1, :bind2, :bind3, :bind4)", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Account Created!";
        } else {
            echo "<br> Creation Failed.";
        }
    }

    function updateWeightRequest(){
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['upWeightUser'],
            ":bind2" => $_POST['updateWeight']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("UPDATE Users SET Weight=:bind2 WHERE Phone=:bind1", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Updated Weight!";
        } else {
            echo "<br> Update Failed.";
        }
    }

    function updateHeightRequest(){
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['upHeightUser'],
            ":bind2" => $_POST['updateHeight']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("UPDATE Users SET Height=:bind2 WHERE Phone=:bind1", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Updated Height!";
        } else {
            echo "<br> Update Failed.";
        }
    }

    function showUsersRequest() {
        global $db_conn;

        $user = $_GET['showUser'];

        $result = executePlainSQL("SELECT * FROM Users WHERE Phone='" . $user . "'");
        echo "<br>Retrieved data from table Users:<br>";
        echo "<table>";
        echo "<tr><th>Phone</th><th>Name</th><th>Weight</th><th>Height</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["PHONE"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["WEIGHT"] . "</td><td>" . $row["HEIGHT"] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function showHeightByWeightGroup() {
        global $db_conn;

        $result = executePlainSQL("SELECT WEIGHT, AVG(HEIGHT) as averageHeight FROM Users GROUP BY WEIGHT HAVING 
                                                          AVG(HEIGHT) > (SELECT AVG(HEIGHT) FROM Users)");
        echo "<br>Retrieved data from table Users:<br>";
        echo "<table>";
        echo "<tr><th>Weight</th><th>Average Height</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["WEIGHT"] . "</td><td>" . $row["AVERAGEHEIGHT"] . "</td></tr>";
        }

        echo "</table>";

    }


// ------------------ START OF GOALS FUNCTIONS --------------------

    function showGoalsRequest() {
        global $db_conn;

        $user = $_GET['showGoalsUser'];

        $result = executePlainSQL("SELECT * FROM SetsGoals WHERE Phone='" . $user . "'");

        echo "<br>Retrieved data from table SetsGoals:<br>";
        echo "<table>";
        echo "<tr><th>Phone</th><th>Goal Name</th><th>Quantity</th><th>Progress</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["PHONE"] . "</td><td>" . $row["GOAL_NAME"] . "</td><td>" . $row["GOAL_QUANTITY"] . "</td><td>" . $row["GOAL_PROGRESS"] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function insertGoalRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['insGoalUser'],
            ":bind2" => $_POST['insGoalName'],
            ":bind3" => $_POST['insGoalQ'],
            ":bind4" => $_POST['insGoalP']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("INSERT INTO SetsGoals VALUES (:bind1, :bind2, :bind3, :bind4)", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Goal Added!";
        } else {
            echo "<br> Add Failed.";
        }
    }

    function updateGoalRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['upGoalUser'],
            ":bind2" => $_POST['upGoalName'],
            ":bind3" => $_POST['upProgress']
        );
        
        $alltuples = array (
            $tuple
        );

        executeBoundSQL("UPDATE SetsGoals SET Goal_Progress=:bind3 WHERE Phone=:bind1 AND Goal_Name=:bind2", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Updated Goal!";
        } else {
            echo "<br> Update Failed.";
        }
    }

    function deleteGoalRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['delGoalUser'],
            ":bind2" => $_POST['delGoalName']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("DELETE FROM SetsGoals WHERE Phone=:bind1 AND Goal_Name=:bind2", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Deleted Goal!";
        } else {
            echo "<br> Delete Failed.";
        }
    }

    //Query: Division Rubric Requirement
    function showRockstars(){
        global $db_conn;

        $result = findRockstars();

        echo "<br>Users who have achieved all their fitness goals:<br>";
        echo "<table>";
        echo "<tr><th>Name</th><th>Phone</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["NAME"] . "</td><td>" . $row["PHONE"] . "</td></tr>";
        }

        echo "</table>";
    }

    //Query: Division Rubric Requirement
    function findRockstars()
    {
        global $db_conn;

        //Division via Method 2 - Without using EXCEPT
        $sqlSearchCommand = "SELECT U.Phone, U.Name
                            FROM Users U
                            WHERE NOT EXISTS (
                            SELECT G.Goal_Name
                            FROM SetsGoals G
                            WHERE G.Goal_Quantity > G.Goal_Progress AND U.Phone = G.Phone)";

        return executePlainSQL($sqlSearchCommand);
    }

// ------------------ START OF CALORIC BALANCE FUNCTIONS --------------------


    function CountCalBalRequest() {
        global $db_conn;
        global $success;

        $user = $_GET['countCalBalUser'];

        $result = executePlainSQL("SELECT Count(*) FROM Tracks WHERE Phone='" . $user . "'");

        if (($row = oci_fetch_row($result)) != false) {
            echo "The number of days you have logged Caloric Balance: " . $row[0] . "<br>";
        }

        if (!$success) {
            echo "<br> Query Failed.";
        }
    }

    function insertCBRequest() {
        global $db_conn;
        global $success;

        $userPhone = $_POST['logCaloricBalanceUser'];


        $tuple = array (
            ":bind1" => $_POST['insDate'],
            ":bind2" => $_POST['insIntake'],
            ":bind3" => $_POST['insBurned']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("INSERT INTO CaloricBalance VALUES (to_date(:bind1,'YYYY-MM-DD'), :bind2, :bind3)", $alltuples);

        $tracksTuple = array(
                ":bind1" => $userPhone,
                ":bind2" => $_POST['insDate']
        );

        $tracksTuples = array($tracksTuple);

        executeBoundSQL("INSERT INTO Tracks VALUES (:bind1, to_date(:bind2, 'YYYY-MM-DD'))", $tracksTuples);

        OCICommit($db_conn);

        if ($success) {
            echo "Logged Calories!";
        } else {
            echo "<br> Log Failed.";
        }
    }

    //Queries: Aggregation with Group By Rubric Requirement
    function calculateAverageCaloriesBurned(){
        global $db_conn;

        // If we want to treat NULL "burned" values as 0, change cmdstr to SELECT AVG(COALESCE(Burned, 0)) FROM CaloricBalance;
        $result = executePlainSQL("SELECT AVG(Burned) FROM CALORICBALANCE");

        if (($row = oci_fetch_row($result)) != false) {
            echo "The average calories burned by all users: " . round($row[0],2) . "<br>";
        }
    }

// ------------------ START OF APPOINTMENT FUNCTIONS --------------------
    function insertAppointmentRequest() {
        global $db_conn;
        global $success;

        $trainerId = $_POST['appointmentTrainer'];
        $trainerResult = executePlainSQL("SELECT Phone FROM Trainers WHERE TrainerID='" . $trainerId . "'");
        $trainerPhone = oci_fetch_row($trainerResult)[0];

        $gymName = $_POST['appointmentGym'];
        $gymResult = executePlainSQL("SELECT Address, PostalCode FROM Gyms WHERE Name='" . $gymName . "'");
        $gymRow = oci_fetch_row($gymResult);
        $gymAddress = $gymRow[0];
        $gymPostalCode = $gymRow[1];

        $tuple = array (
                ":bind1" => $_POST['appointmentUser'],
                ":bind2" => $_POST['appointmentTrainer'],
                ":bind3" => $trainerPhone,
                ":bind4" => $gymAddress,
                ":bind5" => $gymPostalCode,
                ":bind6" => $_POST['appointmentDate'],
                ":bind7" => $_POST['startTime'],
                ":bind8" => $_POST['endTime'],
                ":bind9" => $_POST['sessionType']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("INSERT INTO BooksAppointment(Phone, TrainerPhone, TrainerID, Address, PostalCode, ApptDate, StartTime, EndTime, SessionType)
                VALUES (:bind1, :bind3, :bind2, :bind4, :bind5, to_date(:bind6,'YYYY-MM-DD'), :bind7, :bind8, :bind9)", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Booked Appointment!";
        } else {
            echo "<br> Booking Failed.";
        }
    }

    function deleteAppointmentRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['deleteAppointment']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("DELETE FROM BooksAppointment WHERE ID=:bind1", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Deleted Appointment!";
        } else {
            echo "<br> Delete Failed.";
        }
    }

    function showAppointmentsRequest() {
        global $db_conn;

        $user = $_GET['showAppointmentsUser'];

        $result = executePlainSQL("SELECT * FROM BooksAppointment WHERE Phone='" . $user . "'");

        echo "<br>Retrieved data from table BooksAppointments:<br>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Phone</th><th>Trainer Phone</th><th>Trainer ID</th><th>Address</th><th>PostalCode</th><th>Appointment Date</th><th>Start Time</th><th>End Time</th><th>Session Type</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["PHONE"] . "</td><td>" . $row["TRAINERPHONE"] . "</td><td>" . $row["TRAINERID"] . "</td><td>" . $row["ADDRESS"] . "</td><td>" . $row["POSTALCODE"] . "</td><td>" . $row["APPTDATE"] . "</td><td>" . $row["STARTTIME"] . "</td><td>" . $row["ENDTIME"] . "</td><td>" . $row["SESSIONTYPE"] . "</td></tr>";
        }

        echo "</table>";
    }

// ------------------ START OF WORKOUTS FUNCTIONS --------------------

    function insertDoWorkoutRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['doWorkoutUser'],
            ":bind2" => $_POST['workout']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("INSERT INTO Does(phone, workouts_name) VALUES(:bind1, :bind2)", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Recorded Workout!";
        } else {
            echo "<br> Recording Failed.";
        }
    }

    function showWorkoutsRequest() {
        global $db_conn;

        $user = $_GET['showWorkoutsUser'];

        $result = executePlainSQL("SELECT * FROM Does WHERE Phone='" . $user . "'");

        echo "<br>Retrieved data from table Does:<br>";
        echo "<table>";
        echo "<tr><th>Phone</th><th>Workout Name</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["PHONE"] . "</td><td>" . $row["WORKOUTS_NAME"]  . "</td></tr>";
        }

        echo "</table>";
    }

    function showMGWorkoutsRequest(){
        global $db_conn;

        $MG = $_GET['insMG'];

        // Sanitization: Check for a semicolon in the input to prevent simple SQL injection attempts
        if (strpos($MG, ';') !== false) {
            echo "Invalid input.";
            return; }

        $result = executePlainSQL(
            "SELECT DISTINCT W.Name FROM Workouts W JOIN Contains C ON W.Name = C.Workouts_Name JOIN Exercise E ON C.Exercise_ID = E.ID WHERE E.MuscleGroup ='" . $MG . "'");

        echo "<br>All Workouts that contain " . $MG . " exercises :<br>";
        echo "<table>";
        echo "<tr><th>Workout Name</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["NAME"] . "</td></tr>";
        }

        echo "</table>";
    }



    
// ------------------ START OF FRIENDS FUNCTIONS --------------------

    function insertFriendRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['insFriendUser'],
            ":bind2" => $_POST['insUserToFriend']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("INSERT INTO FriendsWith VALUES (:bind1, :bind2)", $alltuples);
        executeBoundSQL("INSERT INTO FriendsWith VALUES (:bind2, :bind1)", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Friend Added!";
        } else {
            echo "<br> Add Failed.";
        }
    }

    function deleteFriendRequest() {
        global $db_conn;
        global $success;

        $tuple = array (
            ":bind1" => $_POST['delFriendUser'],
            ":bind2" => $_POST['delUserToFriend']
        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("DELETE FROM FriendsWith WHERE friend1_Phone=:bind1 AND friend2_Phone=:bind2", $alltuples);
        executeBoundSQL("DELETE FROM FriendsWith WHERE friend1_Phone=:bind2 AND friend2_Phone=:bind1", $alltuples);
        OCICommit($db_conn);

        if ($success) {
            echo "Friend Removed.";
        } else {
            echo "<br> Remove Failed.";
        }
    }

    function showFriendsRequest() {
        global $db_conn;

        $user = $_GET['showFriendsUser'];

        $result = executePlainSQL("SELECT * FROM FriendsWith WHERE friend1_Phone='" . $user . "'");

        echo "<br>Retrieved data from table Friends:<br>";
        echo "<table>";
        echo "<tr><th>User</th><th>Friend</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            // let $user carry the name of the user
            $user = executePlainSQL("SELECT * FROM Users WHERE Phone='" . $row["FRIEND1_PHONE"] . "'");
            $userRow = OCI_Fetch_Array($user, OCI_BOTH);
            $friend = executePlainSQL("SELECT * FROM Users WHERE Phone='" . $row["FRIEND2_PHONE"] . "'");
            $friendRow = OCI_Fetch_Array($friend, OCI_BOTH);
            echo "<tr><td>" . $userRow["NAME"] . "</td><td>" . $friendRow["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    //Query: Aggregation with HAVING Rubric Requirement
    function findFriends($minThreshold, $maxThreshold) {
        global $db_conn;

        // Sanitization checks
        if (!is_numeric($minThreshold) || !is_numeric($maxThreshold)) {
            echo "The input must be a number.";
            return; // Stop the function if the input is not numeric
        }

        $result = executePlainSQL(
            "SELECT Users.Phone, Users.Name, AVG(CaloricBalance.Burned) as AvgBurned FROM Users JOIN Tracks ON Users.Phone = Tracks.Phone JOIN CaloricBalance ON Tracks.LogDate = CaloricBalance.LogDate GROUP BY Users.Phone, Users.Name HAVING AVG(CaloricBalance.Burned) BETWEEN " . $minThreshold . " AND " . $maxThreshold);

        echo "<br>Users within your calorie burn range:<br>";
        echo "<table>";
        echo "<tr><th>Phone</th><th>Name</th><th>Avg Calories Burned</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1]  . "</td><td>" . $row[2]  . "</td></tr>";
        }

        echo "</table>";
    }

// ------------------ SELECTION ---------

function BMIRequest() {
    global $db_conn;
    global $success;

    $num = $_GET['numSelect'];
    $where = "u.Weight > 150 AND u.Height > 150";

    if ($num == "1") {
        $where = "u.Weight > 150 OR u.Height > 150";
    } else if ($num == "2") {
        $where = "u.Weight < 150 AND u.Height < 150";
    } else if ($num == "3") {
        $where = "u.Weight < 150 OR u.Height < 150";
    }

    $result = executePlainSQL("SELECT * FROM Users u WHERE " . $where);

    if (!$success) {
        echo "<br> Query Failed.";
    } else {
        echo "<br> Showing users with " . $where;
        echo "<table>";
        echo "<tr><th>Phone</th><th>Name</th><th>Weight</th><th>Height</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1]  . "</td><td>" . $row[2]  . "</td><td>" . $row[3]  . "</td></tr>";
        }

        echo "</table>";
    }
}


// ------------------ HTTP METHOD FUNCTIONS --------------------

    // HANDLE ALL POST ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('updateWeightRequest', $_POST)) {
                updateWeightRequest();
            } else if (array_key_exists('updateHeightRequest', $_POST)) {
                updateHeightRequest();
            } else if (array_key_exists('insertUserRequest', $_POST)) {
                insertUserRequest();
            } else if (array_key_exists('insertGoalRequest', $_POST)) {
                insertGoalRequest();
            } else if (array_key_exists('deleteGoalRequest', $_POST)) {
                deleteGoalRequest();
            } else if (array_key_exists('updateGoalRequest', $_POST)) {
                updateGoalRequest();
            } else if (array_key_exists('insertCBRequest', $_POST)) {
                insertCBRequest();
            } else if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
            } else if (array_key_exists('insertAppointmentRequest', $_POST)) {
                insertAppointmentRequest();
            } else if (array_key_exists('deleteAppointmentRequest', $_POST)) {
                deleteAppointmentRequest();
            } else if (array_key_exists('insertDoWorkoutRequest', $_POST)) {
                insertDoWorkoutRequest();
            } else if (array_key_exists('insertFriendRequest', $_POST)) {
                insertFriendRequest();
            } else if (array_key_exists('deleteFriendRequest', $_POST)) {
                deleteFriendRequest();
            }

            disconnectFromDB();
        }
    }

    // HANDLE ALL GET ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('countCalBal', $_GET)) {
                CountCalBalRequest();
            } else if (array_key_exists('showGoalsRequest', $_GET)) {
                showGoalsRequest();
            } else if (array_key_exists('showAppointmentsRequest', $_GET)) {
                showAppointmentsRequest();
            } else if (array_key_exists('showWorkoutsRequest', $_GET)) {
                showWorkoutsRequest();
            } else if (array_key_exists('showMuscleGroupedWorkouts', $_GET)) {
                showMGWorkoutsRequest();
            } else if (array_key_exists('showUsersRequest', $_GET)) {
                showUsersRequest();
            } else if (array_key_exists('showTablesRequest', $_GET)) {
                showTablesRequest();
            } else if (array_key_exists('calculateAverageCaloriesBurned', $_GET)) {
                calculateAverageCaloriesBurned();
            } else if (array_key_exists('findFriends', $_GET)) {
                $minThreshold = $_GET['minThreshold'];
                $maxThreshold = $_GET['maxThreshold'];
                findFriends($minThreshold, $maxThreshold);
            } else if (array_key_exists('showFriendsRequest', $_GET)) {
                showFriendsRequest();
            } else if (array_key_exists('showRockstars', $_GET)) {
                showRockstars();
            } else if (array_key_exists('weightHeightRequest', $_GET)) {
                    showHeightByWeightGroup();
            } else if (array_key_exists('BMIRequest', $_GET)) {
                BMIRequest();
            }

            disconnectFromDB();
        }
    }

    if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
        handlePOSTRequest();
    } else if (isset($_GET['countCalBalRequest']) || isset($_GET['displayRequest']) || isset($_GET['calculateAverageCaloriesBurned']) || isset($_GET['findFriends']) || isset($_GET['showRockstars']) ) {
        handleGETRequest();
    } else if (isset($_GET['login'])) {
        loginUser();
    }
?>

