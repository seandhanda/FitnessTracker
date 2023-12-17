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

        <h1>Welcome to Fitness Tracker</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>

        <hr />
        <h2>Quick View</h2>
        <form method="GET" action="home.php"> <!--refresh page when submitted-->
            <input type="hidden" id="showTablesRequest" name="showTablesRequest">
            Table :
            <?php
                connectToDB();
            ?>
            <select name="table"> 
                <option value=""> -- Select -- </option>
                <?php
                    $result = executePlainSQL("SELECT table_name FROM user_tables");
                    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                        $selected = '';
                        if (isset($_GET['table']) && $_GET['table'] == $row['TABLE_NAME']) {
                            $selected = 'selected';
                        }
                        echo "<option value=\"". $row['TABLE_NAME']. "\" $selected>". $row['TABLE_NAME'] . "</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Select"></p>
            <br /><br />
            Attributes :
            <br /><br />
            <?php
                connectToDB();
            ?>
            
            <?php
                $result = executePlainSQL("SELECT column_name FROM user_tab_columns WHERE table_name = '" . $_GET['table'] . "'");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<input type=\"checkbox\" name=\"attributes[]\" value=\"" . $row['COLUMN_NAME'] . "\">" . $row['COLUMN_NAME'] . "<br />";
                }
            ?>
            <br /><br />
            
            <input type="submit" name="displayRequest"></p>
        </form>
    </body>
</html>