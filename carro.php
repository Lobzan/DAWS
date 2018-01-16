<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  $title = "Plantas el Caminàs -> ";


  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Producto.php';
  require './include/ElCaminas/Productos.php';
  use ElCaminas\Carrito;



  $carrito = new Carrito();
  //Falta comprobar qué acción: add, delete, empty
$action="view";
if(isset($_GET["action"])){
$action = $_GET["action"];
}
if ($action=="add"){
$carrito->addItem($_GET["id"], $_GET["cantidad"]);
 }
if($action=="delete"){
$carrito->deleteItem($_GET["id"]);
}
if($action=="empty"){
  $carrito->empty();
}

$withRedirect = false;
include("./include/header.php");


?>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<!-- popup modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalle del producto</h4>
      </div>
      <div class="modal-body">
        <iframe src='#' width="100%" height="600px" frameborder=0 style='padding:8px'></iframe>
      </div>
    </div>
  </div>
</div>
<!-- fin popup modal -->


  <div class="row carro">
    <h2 class='subtitle' style='margin:0'>Tu cesta <a id='vaciar' href='./carro.php?action=empty' onclick="return checkVaciar();">(Vaciar)</a></h2>


    <script>
    function checkVaciar(){
        if (confirm('Vas a vaciar tu carrito, esta acción no se podrá deshacer'))
          return true;
        else {
          return false;
        }
    }
    function checkEliminar(){
        if (confirm('¿Deseas eliminar este producto de tu lista?'))
          return true;
        else {
          return false;
        }
    }
  /*  var linkVaciar = document.getElementById("vaciar");
      var linkEliminar = document.getElementById("eliminar");

    linkVaciar.addEventListener("click", function (event) {
            event.preventDefault();
            if (confirm('Vas a vaciar tu carrito, esta acción no se podrá deshacer')) {
                window.location = this.href;
            }
        },  false);
*/
    </script>

    <?php  echo $carrito->toHtml();?>
  </div>
  <script>
          paypal.Button.render({

              env: 'sandbox', // sandbox | production

              // PayPal Client IDs - replace with your own
              // Create a PayPal app: https://developer.paypal.com/developer/applications/create
              client: {
                  sandbox:    'AURtFahgo3cuV-8J35gOhzh0AhTk36fnkHRkuGs-ZBiDoRdzd4NGvRDFFvzkCqmoU3puoZ3FOyS2zkDX',
                  production: '<insert production client id>'
              },

              // Show the buyer a 'Pay Now' button in the checkout flow
              commit: true,

              // payment() is called when the button is clicked
              payment: function(data, actions) {

                  // Make a call to the REST api to create the payment
                  return actions.payment.create({
                      payment: {
                          transactions: [
                              {
                                  amount: { total: '<?php echo $carrito->getTotal(); ?>', currency: 'EUR' }
                              }
                          ]
                      }
                  });
              },

              // onAuthorize() is called when the buyer approves the payment
              onAuthorize: function(data, actions) {

                  // Make a call to the REST api to execute the payment
                  return actions.payment.execute().then(function() {
                      window.alert('Pago completado!');
                      document.location.href = 'gracias.php';
                  });
              }

          }, '#paypal-button-container');

      </script>


<?php
$bottomScripts = array();
$bottomScripts[] = "modalIframeProducto.js";
include("./include/footer.php");
?>
