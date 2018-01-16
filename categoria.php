<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Productos.php';
  require './include/ElCaminas/Producto.php';

  use ElCaminas\Carrito;
  use ElCaminas\Productos;
  use ElCaminas\Producto;
  $productos = new Productos();

  $query = " SELECT * FROM categorias WHERE id = :id";
	$statement = $connect->prepare($query);
	$statement->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
	$statement->execute();
	$count = $statement->rowCount();
	if($count > 0){
		$categoria = $statement->fetch(PDO::FETCH_ASSOC);
	}else{
    http_response_code(404);
    exit();
  }
  $title = "Plantas el Caminàs -> " . $categoria["nombre"];


  require './include/JasonGrimes/Paginator.php';

  use JasonGrimes\Paginator;

  $currentPage = (isset($_GET["currentPage"]) ? $_GET["currentPage"] : 1);
  //En principio este parámetro no estaría en producción. Lo usamos para ir probando el paginador con distintos tamaños
  $itemsPerPage = (isset($_GET["itemsPerPage"]) ? $_GET["itemsPerPage"] : 1);

  // $query = " SELECT COUNT(*) as cuenta FROM productos WHERE id_categoria = :id";
  // $statement = $connect->prepare($query);
  // $statement->bindParam(':id', $_GET["id"], PDO::PARAM_INT);
  // $statement->execute();

  $totalItems = $productos->getCountProductosByCategoria($_GET["id"]);
  include("./include/header.php");

?>
  <div class="row">
    <h2 class='subtitle'><?php echo $categoria["nombre"];?></h2>
    <?php
    foreach($productos->getProductosByCategoria($_GET["id"], $itemsPerPage, $currentPage) as $producto){
       echo $producto->getThumbnailHtml();
    }
    ?>
  </div>
  <div class="row">
    <?php
    $urlPattern = "./categoria.php?id=" . $_GET["id"] . "&itemsPerPage=$itemsPerPage&currentPage=(:num)";
    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    //echo $paginator->toHtml();
    include './include/JasonGrimes/examples/pager.phtml';
    ?>
  </div>
<?php
include("./include/footer.php");
?>
