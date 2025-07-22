<?php
require_once 'models/Cliente.php';

class ClienteController {
  private $clienteModel;

  public function __construct() {
    $this->clienteModel = new Cliente();
  }
  
  // Mostrar lista de clientes
  public function viewCliente(){
    $clientes = $this->clienteModel->getAll();
    include 'views/clientes/viewCliente.php';
  }

  // Mostrar formulario para crear nuevo cliente
  public function create() {
    include 'views/clientes/createCliente.php';
  }

  // Guardar nuevo cliente
  public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'nombre_completo' => $_POST['nombre_completo'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'tipo' => $_POST['tipo'] ?? 'cliente'
      ];

      if ($this->clienteModel->create($data)) {
        header('Location: ' . url('cliente', 'viewCliente'));
      } else {
        // Manejar error al crear cliente
        echo "Error al crear el cliente.";
      }
    }
  }
}
