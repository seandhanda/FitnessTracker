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

        <h1>Manage Account</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>

        <hr />
        <h2>Create Account</h2>
        <form method="POST" action="account.php">
            <input type="hidden" id="insertUserRequest" name="insertUserRequest">
            Phone: <input type="number" name="insPhone" placeholder="(ie. 7781112222)">
            Name: <input type="text" name="insName" placeholder="(ie. John Smith)">
            Weight: <input type="number" name="insWeight" placeholder="(in lbs)">
            Height: <input type="number" name="insHeight" placeholder="(in cm)">
            <input type="submit" value="Create Account" name="insertSubmit">    
        </form>

        <hr />
        <h2>Update Weight</h2>
        <form method="POST" action="account.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateWeightRequest" name="updateWeightRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="upWeightUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            New Weight: <input type="number" name="updateWeight" placeholder="(in lbs)">

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />
        <h2>Update Height</h2>
        <form method="POST" action="account.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateHeightRequest" name="updateHeightRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="upHeightUser"> 
                        <option value=""> -- Select -- </option> 
            <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
            ?>
            </select> 
            <br /><br />
            New Height: <input type="number" name="updateHeight" placeholder="(in cm)">

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />
        <h2>Display User Info</h2>
        <form method="GET" action="account.php"> <!--refresh page when submitted-->
            <input type="hidden" id="showUsersRequest" name="showUsersRequest">
            User :
            <?php
                connectToDB();
            ?>
            <select name="showUser"> 
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

        <h2> Find out what weight class has an average height greater than the average height of all users!</h2>
        <form method="GET" action="account.php">
            <input type="hidden" id="weightHeightRequest" name="weightHeightRequest">
            <input type="submit" value="Find Out Now!" name="displayRequest">
        </form>

        <hr />
        <h2>BMI Distribution Search</h2>
        <form method="GET" action="account.php"> <!--refresh page when submitted-->
            <input type="hidden" id="BMIRequest" name="BMIRequest">
            User :
            <select name="numSelect"> 
                <option value=""> -- Select -- </option>
                <option value="0"> Weight > 150 AND Height > 150 </option>
                <option value="1"> Weight > 150 OR Height > 150 </option>
                <option value="2"> Weight < 150 AND Height < 150 </option>
                <option value="3"> Weight < 150 OR Height < 150 </option>
            </select>
            <br /><br />
            <input type="submit" name="displayRequest"></p>
        </form>
        
	</body>
</html>