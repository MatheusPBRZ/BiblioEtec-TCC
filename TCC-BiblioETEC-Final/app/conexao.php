<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "biblioteca";

    try{
        $conn = new PDO(dsn: "mysql:host=$servername;dbname=$dbname;charset=UTF8", username: $username, password: $password);
        $conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    }

    catch(PDOException $erro){
        header(header: "Ocorreu o seguinte erro: ".$erro->getMessage());
    }
?>