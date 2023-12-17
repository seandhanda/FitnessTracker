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
        

        <h1>Manage Friends</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>

        <!--Query: Aggregation with HAVING Rubric Requirement -->
        <hr />
        <h2>Find Friends with Similar Daily Routines</h2>
        <p>Enter your daily calorie burn range to find users with similar routines.</p>
        <form method="GET" action="friends.php">
            <input type="hidden" id = "findFriends" name = "findFriends">

            Min Average Calories Burned: <input type="number" name= "minThreshold" min ="0"> <br /><br />

            Max Average Calories Burned: <input type= "number" name= "maxThreshold" min= "0"> <br/> <br/>

            <input type = "submit" value = "Find Friends" name ="findFriends"></p>
        </form>

        <hr />
        <h2>Add Friends</h2>
        <form method="POST" action="friends.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertFriendRequest" name="insertFriendRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="insFriendUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            Add :
            <?php
                connectToDB();
            ?>
            <select name="insUserToFriend"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />

            <input type="submit" value="Add Friend" name="insertSubmit"></p>
        </form>

        <hr />
        <h2>Remove Friends</h2>
        <form method="POST" action="friends.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteFriendRequest" name="deleteFriendRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="delFriendUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            Remove :
            <?php
                connectToDB();
            ?>
            <select name="delUserToFriend"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select>
            <br /><br />

            <input type="submit" value="Remove" name="insertSubmit"></p>
        </form>

        <hr />
        <h2>Display Friends</h2>
        <form method="GET" action="friends.php"> <!--refresh page when submitted-->
            <input type="hidden" id="showFriendsRequest" name="showFriendsRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="showFriendsUser"> 
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
	</body>
</html>