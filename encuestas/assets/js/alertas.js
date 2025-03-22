function mostrarAlerta(titulo, mensaje, tipo = "success", redirigir = null) {
    const customAlert = document.getElementById("customAlert");
    const customAlertTitle = document.getElementById("customAlertTitle");
    const customAlertMessage = document.getElementById("customAlertMessage");
    const customAlertClose = document.getElementById("customAlertClose");

    // Configurar el título y el mensaje
    customAlertTitle.innerText = titulo;
    customAlertMessage.innerText = mensaje;

    // Cambiar el color del botón según el tipo
    customAlertClose.style.backgroundColor = tipo === "success" ? "#28a745" : "#dc3545";

    // Mostrar la ventana emergente
    customAlert.style.display = "flex";

    // Cerrar la ventana emergente al hacer clic en el botón
    customAlertClose.onclick = () => {
        customAlert.style.display = "none";
        if (redirigir) {
            window.location.href = redirigir;
        }
    };

    // Redirigir después de un tiempo si es necesario
    if (redirigir) {
        setTimeout(() => {
            customAlert.style.display = "none";
            window.location.href = redirigir;
        }, 3000); // Redirigir después de 3 segundos
    }
}