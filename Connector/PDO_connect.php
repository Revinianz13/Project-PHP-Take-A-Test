
<?php

$dsn = "127.0.0.1";
$user = "root";
$password = "soula12345";
$dbname = "Evaluate";


try {
    $conn = new PDO("mysql:host=$dsn;dbname=$dbname;",$user,$password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "<script>alert('connected');</script>";
}
catch(PDOException $e)
{
    echo "<script>alert(' Not connected');</script>";

}
?>