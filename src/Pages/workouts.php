<!--This file is built on and modified from the starter file provided at
https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/php-setup.html
-->

<html>
    <head>
        <title>Fitness Tracker</title>
    </head>
    <body>
        <a href="home.php"> Home </a>
        <a> | </a>
        <a href="account.php"> Account </a>
        <a> | </a>
        <a href="goals.php"> Goals </a>
        <a> | </a>
        <a href="workouts.php"> Workouts </a>
        <a> | </a>
        <a href="calories.php"> Caloric Balance </a>
        <a> | </a>
        <a href="appointment.php"> Appointments </a>
        <a> | </a>
        <a href="friends.php"> Friends </a>
        <a> | </a>
        

        <h1>Manage Workouts</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>
        <hr />
        <h2>Select a Workout to Do</h2>
        <form method="POST" action="workouts.php">
            <input type="hidden" id="insertDoWorkoutRequest" name="insertDoWorkoutRequest">
            User :
            <?php
            connectToDB();
            ?>
            <select name="doWorkoutUser">
                <option value=""> -- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
                ?>
            </select>
            <br /><br />
            Workout :
            <select name="workout">
                <option value=""> -- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Workouts");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['NAME']. "\">". $row['NAME'] . "</option>";
                }

                ?>
            </select>
            <br /><br />
        <input type="submit" value="Select Workout" name="insertSubmit">
        </form>

        <h2> Display Workouts </h2>
        <form method="GET" action="workouts.php">
            <input type="hidden" id="showWorkoutsRequest" name="showWorkoutsRequest">
            User :
            <?php
            connectToDB();
            ?>
            <select name="showWorkoutsUser">
                <option value=""> -- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
                ?>
            </select>
            <br /><br />
            <input type="submit" value="Show Workouts" name="displayRequest"></p>
        </form>

        <h2> Display Workouts Containing Exercises Targetting Specified Muscle Group!</h2>
        <form method="GET" action="workouts.php">
            <input type="hidden" id="showMuscleGroupedWorkouts" name="showMuscleGroupedWorkouts">
            Muscle Group: <input type="text" name="insMG" placeholder="(ie. Upper Body)">
            <br /><br />
            <input type="submit" value="Show Workouts" name="displayRequest"></p>
        </form>

        
	</body>
</html>