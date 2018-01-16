<?php

namespace ElCaminas;
use \PDO;
use \ElCaminas\Producto;
class Carrito
{
    protected $connect;
    /** Sin parámetros. Sólo crea la variable de sesión
    */
    public function __construct()
    {
        global $connect;
        $this->connect = $connect;
        if (!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = array();
        }
    }
    private function getRedirect(){
      $redirect =  isset($_GET['redirect']) ? $_GET['redirect'] : '/tienda2/index.php';
      $redirect = urldecode($redirect);
      return $redirect;
    }
    public function addItem($id, $cantidad){
        $_SESSION['carrito'][$id] = $cantidad;
    }
    public function deleteItem($id){
      unset($_SESSION['carrito'][$id]);
    }
    public function empty(){
      unset($_SESSION['carrito']);
      self::__construct();
    }
    public function howMany(){
      return count($_SESSION['carrito']);
    }
    public function cestaMenu(){
      $redirect = "";
      $redirect = $this->getRedirect();
      $url4red =  "&redirect=" . urlencode($redirect);


$str2 = <<<heredoc
      <li>

          <a href="./carro.php?action=view$url4red" style="padding-left:250px;">
           <span class="fa fa-shopping-cart"></span>Mi Cesta
           </a>
      </li>
heredoc;

      return $str2;
    }

public function getTotal(){

  $totalCarrito = 0;
  foreach($_SESSION['carrito'] as $key => $cantidad){
    $producto = new Producto($key);
    $totalCarrito = $producto->getPrecioReal() * $cantidad;
}
return $totalCarrito;
}


    public function toHtml(){
      //NO USAR, de momento
      $total = 0;
      $this->getRedirect();
      $str = <<<heredoc
      <table class="table">
        <thead> <tr> <th>#</th> <th>Producto</th> <th>Cantidad</th> <th>Precio</th> <th>Total</th><th>Eliminar</th></tr> </thead>
        <tbody>
heredoc;
      if ($this->howMany() > 0){
        $i = 0;
        foreach($_SESSION['carrito'] as $key => $cantidad){
          $producto = new Producto($key);
          $i++;
          $subtotal = $producto->getPrecioReal() * $cantidad;
          $subtotalTexto = number_format($subtotal , 2, ',', ' ') ;
          $total +=  $subtotal;
          $str .= "<tr><th scope='row'>$i</th><td><a href='" .  $producto->getUrl() . "'>" . $producto->getNombre() . "</a>";
          $str .= "<a class='open-modal' title='Haga clic para ver el detalle del producto' href='" .  $producto->getUrl() . "'>";
          $str .= "<span style='color:#000' class='fa fa-external-link'></span>";
          $str .= "</a></td><td>$cantidad</td><td>" .  $producto->getPrecioReal() ." €</td><td>$subtotalTexto €</td>"."<td><a href='?action=delete&id=".$producto->getId() ."' onclick='return checkEliminar();'><span class='fa fa-times'></span></a></td></tr>";
        }
      }
      $redirect = $this->getRedirect();
      $str .= <<<heredoc
        </tbody>

      </table>

        <hr /><h4 style="text-align:right; font-weight:bold;">Total: $total</h4><hr />

          <h5 style="text-align:right;" ><a href=$redirect>Seguir comprando</a> <div id="paypal-button-container"></div> <a href=#>Tramitar pedido</a></h5>

heredoc;
      return $str;
    }
}
