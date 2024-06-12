<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset= "UTF8">
    <meta name = "viewport" content="width=device-width, initial-scale=1.0">
    <title>SimplePHPPage w Dropdown</title>
</head>
<body>
   <h1>Select Your Option</h1>

   <?php
   //database config
   $servername = "localhost";
   $username = "root";
   $password = "root";
   $dbname = "new_to_php";

   //connection
   $conn = new mysqli($servername, $username, $password, $dbname);

   //check
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   //fetch data
   $sql = "SELECT key_value, label FROM options";
   $result = $conn->query($sql);
   ?>

    <form action="process.php" method="post">
        <label  for="name">Name: </label>
        <input type ="text" id="name" name="name" required> <br><br>
        <label for="dropdown">Choose an option:</label>
        <select id="dropdown" name="dropdown"><br><br>
        <?php
        //create options
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                echo "<option value=\"" .($row['key_value']) . "\">" .
                    ($row['label']) . "</option/";
            }
        } else {
                echo "<option value=\"\"> NO options available</option>";
        }
        ?><br>
        </select> <br><br>
        <input type ="submit" value="Submit"><br><br>
    </form>
   <?php
       //close
       $conn->close();
   ?>
</body>
</html>