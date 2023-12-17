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
        

        <h1>Manage Appointments</h1>
        <hr />
        <h2>Results</h2>
        <?php
            include("../PHP/logic.php");
        ?>

        <h2>Create an Appointment </h2>
        <form method="POST" action="appointment.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertAppointmentRequest" name="insertAppointmentRequest">
            User:
            <?php
            connectToDB();
            ?>
            <select name="appointmentUser">
                <option value=""> -- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
                ?>
            </select>
            <br /><br />
            Trainer:
            <select name="appointmentTrainer">
                <option value="">-- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT u.phone, u.name, t.specialty, t.TrainerID FROM Users u, Trainers t WHERE u.phone = t.phone");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['TRAINERID']. "\">". $row['NAME'] . " - " . $row['PHONE']. " - " . $row['SPECIALTY']."</option>";
                }
                ?>
            </select>
            <br /><br />
            Gym:
            <select name="appointmentGym">
                <option value="">-- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Gyms");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['NAME']. "\">". $row['NAME'] . " - " . $row['ADDRESS']. "</option>";

                }
                ?>
            </select>
            <br /><br />
            Appointment Date: <input type="date" name="appointmentDate"> <br /><br />
            Start Time: <input type="time" name="startTime"> <br /><br />
            End Time: <input type="time" name="endTime"> <br /><br />
            Session Type: <input type="text" name="sessionType" maxlength="20"> <br /><br />

            <input type="submit" value="Create Appointment" name="insertSubmit">
        </form>

        <hr />

        <h2> Delete Appointment </h2>
        <form method="POST" action="appointment.php">
            <input type="hidden" id="deleteAppointmentRequest" name="deleteAppointmentRequest">
            Appointment :
            <?php
                connectToDB();
            ?>
            <select name="deleteAppointment">
                <option value=""> -- Select -- </option>
                <?php
                    $result = executePlainSQL("SELECT ba.id, u1.name AS username, u2.name AS trainername, ba.apptdate, ba.starttime, ba.endtime FROM Users u1,
                                        Users u2, BooksAppointment ba WHERE u1.phone = ba.phone and u2.phone = ba.trainerphone");
                    while($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                        echo "<option value=\"". $row['ID']. "\">". $row['USERNAME'] . " with " . $row['TRAINERNAME']
                            . " on " . $row['APPTDATE'] . " at " . $row['STARTTIME'] . "-" . $row['ENDTIME']. "</option>";
                    }

                ?>    
            </select>
            <br /><br />
            <input type="submit" value="Delete Appointment" name="insertSubmit">
        </form>
        <hr/>

        <h2> Display Appointments </h2>
        <form method="GET" action="appointment.php">
            <input type="hidden" id="showAppointmentsRequest" name="showAppointmentsRequest">
            User :
            <?php
            connectToDB();
            ?>
            <select name="showAppointmentsUser">
                <option value=""> -- Select -- </option>
                <?php
                $result = executePlainSQL("SELECT * FROM Users");
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<option value=\"". $row['PHONE']. "\">". $row['NAME'] . " - " . $row['PHONE']. "</option>";
                }
                ?>
            </select>
            <br /><br />
            <input type="submit" value="Show Appointments" name="displayRequest"></p>
        </form>

        
	</body>
</html>