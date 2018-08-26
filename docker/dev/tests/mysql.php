 <?php
$servername = "db";
$username = "devuser";
$password = "devpass";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: \n" . $conn->connect_error);
}
echo "Connected successfully \n";