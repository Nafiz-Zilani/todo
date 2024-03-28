<?php

include_once("config.php");

//Connect to MySQL
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(!$connection){
    throw new Exception("Can't connect.\n");
} else{
    echo "Connected.\n";
    //Inset a rec
    //mysqli_query($connection,"INSERT INTO tasks (task, date) VALUES ('Study on DevOps secend st Module', '2024-02-05')");
//    $result = mysqli_query($connection, "SELECT * FROM tasks");
//    while ($data = mysqli_fetch_assoc($result)){
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//    }

    //mysqli_query($connection, "DELETE FROM tasks")
    //End connection
    mysqli_close($connection);
}

