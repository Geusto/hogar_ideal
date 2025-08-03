<?php
require_once 'models/Venta.php';

class VentaController {
  private $ventaModel;

  public function __construct() {
    $this->ventaModel = new Venta();
  }

  public function index(){
    $ventas = $this->ventaModel->getAll();
    include 'views/ventas/index.php';
  }

}
