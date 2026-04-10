// Esperar a que cargue el documento
$(document).ready(function(){
  $("#contacto").submit(function(event){
    event.preventDefault(); // Evita envío normal

    // Obtener valores
    var nombre = $("#nombre").val().trim();
    var email = $("#email").val().trim();
    var telefono = $("#telefono").val().trim();

    // Validar que teléfono no esté vacío
    if (telefono === "") {
      $("#mensaje")
        .text("Por favor, ingresa tu teléfono.")
        .css("color", "red")
        .fadeIn()
        .delay(2000)
        .fadeOut();
      return; // Salir
    }

    // Si pasa la validación
    $("#mensaje")
      .text("Formulario enviado correctamente.")
      .css("color", "green")
      .fadeIn()
      .delay(2000)
      .fadeOut();

    // Aquí podrías enviar datos por AJAX si quisieras
  });
});
