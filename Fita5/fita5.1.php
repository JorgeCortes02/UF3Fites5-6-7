<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    //connexió dins block try-catch:
    //  prova d'executar el contingut del try
    //  si falla executa el catch
    
    if (isset($_POST["pais"])) {
        try {
            $hostname = "localhost";
            $dbname = "world";
            $username = "admin";
            $pw = "Admin@123";
            $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
        } catch (PDOException $e) {
            echo "Error connectant a la BD: " . $e->getMessage() . "<br>\n";
            exit;
        }

        try {
            //preparem i executem la consulta
            $query = $pdo->prepare("SELECT DISTINCT ci.Name as ciNombre, co.Name as coNombre
FROM country co 
JOIN city ci ON co.Code = ci.CountryCode 
WHERE co.name LIKE '%" . $_POST["pais"] . "%';");
            $query->execute();
        } catch (PDOException $e) {
            echo "Error de SQL<br>\n";
            //comprovo errors:
            $e = $query->errorInfo();
            if ($e[0] != '00000') {
                echo "\nPDO::errorInfo():\n";
                die("Error accedint a dades: " . $e[2]);
            }
        }
    }


    ?>

    <form method="post">

        <input type="text" name="pais">
        <input type="submit">

    </form>

    <?php

    $row = $query->fetch();
    while ($row) {
        echo $row['ciNombre'] . " - " . $row['coNombre'] . "<br/>";
        $row = $query->fetch();
    }

    //versió alternativa amb foreach
    /*foreach ($query as $row) {
      echo $row['i']." - " . $row['a']. "<br/>";
    }*/

    //eliminem els objectes per alliberar memòria 
    unset($pdo);
    unset($query)


        ?>

</body>

</html>