<?php
require_once __DIR__ . '/config/database.php';
session_start();

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingresa un nombre de usuario.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "El usuario solo puede contener letras, números y guiones bajos.";
    } else {
        $sql = "SELECT idusuario FROM usuarios WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $username_err = "Este usuario ya está registrado.";
        } else {
            $username = trim($_POST["username"]);
        }
    }

    // Validar contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingresa una contraseña.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validar confirmación de contraseña
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Por favor, confirma tu contraseña.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password !== $confirm_password)) {
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }

    // Obtener otros datos del formulario
    $nombreU = trim($_POST["nombreU"]);
    $apellido_paterno = trim($_POST["apellido_paterno"]);
    $apellido_materno = trim($_POST["apellido_materno"]);
    $fecha_nacimiento = trim($_POST["fecha_nacimiento"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $genero = trim($_POST["genero"]);

    // Si no hay errores, insertar en la base de datos
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO usuarios (username, password_hash, nombreU, apellido_paterno, apellido_materno, fecha_nacimiento, email, telefono, genero) 
                VALUES (:username, :password, :nombreU, :apellido_paterno, :apellido_materno, :fecha_nacimiento, :email, :telefono, :genero)";

        $stmt = $pdo->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(":nombreU", $nombreU, PDO::PARAM_STR);
        $stmt->bindParam(":apellido_paterno", $apellido_paterno, PDO::PARAM_STR);
        $stmt->bindParam(":apellido_materno", $apellido_materno, PDO::PARAM_STR);
        $stmt->bindParam(":fecha_nacimiento", $fecha_nacimiento, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
        $stmt->bindParam(":genero", $genero, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Obtener ID del usuario recién creado
            $idusuario = $pdo->lastInsertId();
            $_SESSION["loggedin"] = true;
            $_SESSION["idusuario"] = $idusuario;
            $_SESSION["username"] = $username;

            // Si venía de una encuesta, redirigirlo a la encuesta
            if (!empty($_SESSION['redirect_url'])) {
                $redirect_url = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']); // Limpiar la variable
                header("Location: $redirect_url");
                exit();
            }

            // Si no, redirigirlo a welcome.php
            header("location: welcome.php");
            exit();
        } else {
            echo "Algo salió mal. Intenta de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<header class="header-container">
    <div class="header_one">
        <a href="index.php"> <img class="logo" src="./imagenes/logo4.webp" alt="logo"></a>    
        <h2 class="title_page">Encuestas Dinámicas</h2>
    </div>     
</header>

<div class="wrapper-register">
    <div class="flex-wrapper">
        <h2>Registro</h2>
        <p>Por favor, ingresa tus datos.</p>    
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="register-fields">
                <div class="form-group grid1">
                    <label>Nombre de usuario</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group grid2">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group grid3">
                    <label>Confirma tu contraseña</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrarse">
            </div>
        </form>
    </div>
</div>    
</body>
</html>
