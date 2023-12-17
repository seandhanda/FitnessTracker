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

        <h1>Track Caloric Balance</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>
        <hr />
        <h2>Log Caloric Balance</h2>
        <form method="POST" action="calories.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertCBRequest" name="insertCBRequest">

            User :
            <?php
            connectToDB();
            ?>
            <select name="logCaloricBalanceUser">
                <option value=""> -- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
                ?>
            </select>
            <br /><br />

            Date: <input type="date" name="insDate"> <br /><br />
            Intake: <input type="number" name="insIntake"> <br /><br />
            Burned: <input type="number" name="insBurned"> <br /><br />

            <input type="submit" value="Log Calories" name="insertSubmit"></p>
        </form>

        <hr />
        <h2>Count Days Logged for Caloric Balance</h2>
        <form method="GET" action="calories.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countCalBalRequest" name="countCalBalRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="countCalBalUser"> 
                        <option value=""> -- Select -- </option>
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select>
            <br /><br />
            <input type="submit" name="countCalBal"></p>
        </form>

        <!--Queries: Aggregation with Group By Rubric Requirement -->
        <hr />
        <h2>Are you Burning Enough Calories? See how you Compare to the Average User!</h2>
        <form method="GET" action="calories.php"> <!--refresh page when submitted-->
        <input type = "hidden" id="calculateAvgCalories" name="calculateAverageCaloriesBurned">
            <input type = "submit" value = "Check Calories Burned by Average Users" name="calculateAverageCaloriesBurned"></p>
        </form>
	</body>
</html>