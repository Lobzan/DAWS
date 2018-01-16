<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  $title = "Plantas el Caminàs -> ";
  include("./include/header.php");
  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Producto.php';
  require './include/ElCaminas/Productos.php';
  use ElCaminas\Producto;


  $p = new Producto(1);



?>

<?php
include("./include/footer.php");
?>
