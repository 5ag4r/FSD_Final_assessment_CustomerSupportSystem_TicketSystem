<?php

function connection()
{
    $servername = "localhost";
    $username   = "np03cs4a240280";
    $password   = "xfGps6OugM";
    $dbname     = "np03cs4a240280";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}
connection();
