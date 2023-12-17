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

        <h1>Manage Goals</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>
        <hr />
        <h2>Set New Goal</h2>
        <form method="POST" action="goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertGoalRequest" name="insertGoalRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="insGoalUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            Goal Name: <input type="text" name="insGoalName"> <br /><br />
            Quantity: <input type="number" name="insGoalQ"> <br /><br />
            Progress: <input type="number" name="insGoalP"> <br /><br />

            <input type="submit" value="Set Goal" name="insertSubmit"></p>
        </form>

        <hr />
        <h2>Delete Goal</h2>
        <form method="POST" action="goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteGoalRequest" name="deleteGoalRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="delGoalUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            Goal Name: <input type="text" name="delGoalName"> <br /><br />

            <input type="submit" value="Delete Goal" name="insertSubmit"></p>
        </form>

        <hr />
        <h2>Update Goal</h2>
        <form method="POST" action="goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateGoalRequest" name="updateGoalRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="upGoalUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            Goal Name: <input type="text" name="upGoalName"> <br /><br />
            New Progress: <input type="number" name="upProgress"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />
        <h2>Display Goals</h2>
        <form method="GET" action="goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="showGoalsRequest" name="showGoalsRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="showGoalsUser"> 
                        <option value=""> -- Select -- </option>
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select>
            <br /><br />
            <input type="submit" name="displayRequest"></p>
        </form>
        <hr />

        <!--Query: Division Rubric Requirement-->
        <h1>Fitness Rockstars! Users That Have Achieved ALL Their Fitness Goals!</h1>
        <hr />
        <h2>Use This As Motivation - You Can Hit Your Goals, Too!</h2>
        <form method="GET" action="goals.php"> <!--refresh page when submitted-->
            <input type="hidden" id="showUsers" name="showRockstars">
            <input type="submit" value = "See Users" name="showRockstars">
        </form>


	</body>
</html>