<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = ($_POST["name"]);
    $selectedOption = ($_POST["dropdown"]);

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

    //fetch
    $sql = "SELECT key_value, label FROM options WHERE key_value = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedOption);
    $stmt->execute();
    $result = $stmt->get_result();

    //check if option exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h1>Selected Option</h1>";
        echo "<p>Key: " .($row['key_value']) . "</p>";
        echo "<p>Label: " .($row['label']). "</p>";
    } else {
        echo "<p>No details found for the selected option.</p>";
    }

    //log to a file
    $logFile = 'form_data.log';
    $currentDateTime = date('Y-m-d H:i:s');
    $logMessage = "[$currentDateTime] Selected Option: $selectedOption\n";

    //attempt to write to the log file
    if (file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX)) {
       echo "<p>Data has been logged successfully.</p>";
    } else{
        echo "<p>Unable to write to log file.</p>";
    }

    //send an email with the form data
    $to = 'cperrine@srclogisticsinc.com';
    $subject = 'Form Submission';
    $message = "Form Submission Received: \n\nSelected Option:   $selectedOption\n";
    $headers = 'From: cperrine@srclogisticsinc.com' . "\r\n" .
        'Reply-To: cperrine@srclogisticsinc.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    if (mail($to, $subject, $message, $headers)) {
        echo "<p>Mail sent successfully.</p>";
    } else {
        echo "<p>Unable to send mail.</p>";
    }

    //close
	$stmt->close();
	$conn->close();
} else {
     echo "<p> Form submission error</p>";
}
?>