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
echo "Connected successfully \n<br><br>";
echo '$servername = '.$servername.'<br>';
echo '$username = '.$username.'<br>';
echo '$password = '.$password.'<br>';
echo <<<EOF
    <br>
    databases found:-<br>
EOF;

$result = mysqli_query($conn,"SHOW DATABASES");
while ($row = mysqli_fetch_array($result)) {
    echo $row[0]."<br>";
}