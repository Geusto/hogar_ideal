<?php
require_once 'models/Agente.php';

class AgenteController {
  private $agenteModel;

  public function __construct() {
    $this->agenteModel = new Agente();
  }
  
  // Mostrar lista de agentes
  public function index(){
    $agentes = $this->agenteModel->getAll();
    include 'views/agentes/index.php';
  }
}
