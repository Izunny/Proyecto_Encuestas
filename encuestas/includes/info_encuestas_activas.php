    <!-- Se muestra los datos principales de todas las encuestas -->
    <?php 
        $username = "root"; 
        $password = ""; 
        $database = "db_encuestas"; 
        $mysqli = new mysqli("localhost", $username, $password, $database); 
        $query = "SELECT * FROM enc_encuestasm";


        echo '<table border="0" cellspacing="2" cellpadding="2"> 
            <tr> 
                <td> <font face="Arial">Value1</font> </td> 
                <td> <font face="Arial">Value2</font> </td> 
                <td> <font face="Arial">Value3</font> </td> 
                <td> <font face="Arial">Value4</font> </td> 
                <td> <font face="Arial">Value5</font> </td> 
            </tr>';

        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                $field1name = $row["idencuesta"];
                $field2name = $row["nombre"];
                $field3name = $row["descripcion"];
                $field4name = $row["idusuario"];
                $field5name = $row["fecha"]; 

                echo '<tr> 
                        <td>'.$field1name.'</td> 
                        <td>'.$field2name.'</td> 
                        <td>'.$field3name.'</td> 
                        <td>'.$field4name.'</td> 
                        <td>'.$field5name.'</td> 
                    </tr>';
            }
            $result->free();
        } 
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>