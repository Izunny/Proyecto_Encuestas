<?php
session_start();
require_once __DIR__ . '/config/database.php';

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingresa tu usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validar contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingresa tu contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Si no hay errores, verificar usuario en la base de datos
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT idusuario, username, password_hash FROM usuarios WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        // Verificar si existe el usuario
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user["password_hash"])) {
                // Iniciar sesión
                $_SESSION["loggedin"] = true;
                $_SESSION["idusuario"] = $user["idusuario"];
                $_SESSION["username"] = $user["username"];

                // Si el usuario venía de una encuesta, redirigirlo de vuelta
                if (!empty($_SESSION['redirect_url'])) {
                    $redirect_url = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']); // Limpiar la variable
                    header("Location: $redirect_url");
                    exit();
                }

                // Si no, redirigir a la página principal
                header("location: welcome.php");
                exit();
            } else {
                $password_err = "La contraseña es incorrecta.";
            }
        } else {
            $username_err = "No se encontró una cuenta con ese usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>

<header class="header-container">
    <div class="header_one">
        <a href="index.php"><img class="logo" src="./imagenes/logo4.webp" alt="logo"></a>
        <h2 class="title_page">Encuestas Dinámicas</h2>
    </div>     
</header>

<div class="wrapper">
    <div class="flex-wrapper login-items">
        <h2>Iniciar Sesión</h2>
    
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Iniciar Sesión">
            </div>
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>
        </form>
    </div>

    <div class="flex-wrapper">
        <img class="home-img" src="imagenes/junta.jpg" alt="">
    </div>
</div>    

</body>
</html>
