// Abre y cierra el menú lateral en pantallas pequeñas
document.addEventListener('DOMContentLoaded', function () {
  var boton = document.getElementById('toggleSidebar');
  var menu = document.getElementById('sidebar');
  if (boton && menu) {
    boton.addEventListener('click', function () {
      menu.classList.toggle('abierto');
    });
  }
});
