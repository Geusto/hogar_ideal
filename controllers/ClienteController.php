<?php
require_once 'models/Cliente.php';

class ClienteController {
  private $clienteModel;

  public function __construct() {
    $this->clienteModel = new Cliente();
  }
  
  // Mostrar lista de clientes
  public function viewCliente(){
    // Obtener todos los clientes y tipos de documento
    $clientes = $this->clienteModel->getAll();
    $tiposDocumento = $this->clienteModel->getTiposDocumento();
    // Filtros desde GET
    $filtros = [
        'buscar' => $_GET['buscar'] ?? '',
        'tipo' => $_GET['tipo'] ?? '',
        'estado' => $_GET['estado'] ?? '',
        'tipoDocumento' => $_GET['tipoDocumento'] ?? ''
    ];

    // Obtener clientes filtrados
    $clientes = $this->clienteModel->filtrarClientes($filtros);

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
        'direccion' => $_POST['direccion'] ?? '',
        'tipo' => $_POST['tipo'] ?? 'cliente',
        'idTipoDocumento' => $_POST['idTipoDocumento'] ?? '',
        'documento' => $_POST['documento'] ?? '',
        'statusC' => 'activo'
      ];

      // Validar que el email no esté en uso
      if ($this->clienteModel->emailEnUso($data['email'])) {
        $msg = 'El email ya está en uso.';
        $tipo = 'error';
        include 'views/clientes/createCliente.php';
        return;
      }

      // Validar que el documento no esté en uso
      if ($this->clienteModel->documentoEnUso($data['documento'])) {
        $msg = 'El documento ya está en uso.';
        $tipo = 'error';
        include 'views/clientes/createCliente.php';
        return;
      }

      if ($this->clienteModel->create($data)) {
        redirect('cliente', 'viewCliente');
      } else {
        // Manejar error al crear cliente
        echo "Error al crear el cliente.";
      }
    }
  }

  // Editar cliente
  public function edit($id) {
    $cliente = $this->clienteModel->getById($id);
    if (!$cliente) {
      // Manejar error si el cliente no existe
      echo "Cliente no encontrado.";
      return;
    }
    include 'views/clientes/editCliente.php';
  }

  // Actualizar cliente
  public function update($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'nombre_completo' => $_POST['nombre_completo'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'tipo' => $_POST['tipo'] ?? 'cliente',
        'idTipoDocumento' => $_POST['idTipoDocumento'] ?? '',
        'documento' => $_POST['documento'] ?? ''
      ];

      // Validar que el email no esté en uso
      if ($this->clienteModel->emailEnUso($data['email'], $id)) {
        redirect('cliente', 'edit', $id, ['msg' => 'El email ya está en uso.', 'tipo' => 'error']);
      } 

      // Validar que el documento no esté en uso
      if ($this->clienteModel->documentoEnUso($data['documento'], $id)) {
        redirect('cliente', 'edit', $id, ['msg' => 'El documento ya está en uso.', 'tipo' => 'error']);
      }

      if ($this->clienteModel->update($id, $data)) {
        redirect('cliente', 'viewCliente', null, ['msg' => 'Cliente actualizado correctamente.', 'tipo' => 'exito']);
      } else {
        redirect('cliente', 'edit', $id, ['msg' => 'No se pudo actualizar el cliente.', 'tipo' => 'error']);
      }
    }
  }

  // cambiar estado del cliente
  public function changeStatus($id) {
    $status = $_GET['status'] ?? null;

    // Validar que status sea 0 o 1
    if (!in_array($status, ['0', '1'], true)) {
        header("Location: ?url=cliente/viewCliente&msg=Estado+inv%C3%A1lido.&tipo=error");
        exit;
    }

    if ($this->clienteModel->updateStatus($id, $status)) {
        header("Location: ?url=cliente/viewCliente&msg=Estado+del+cliente+actualizado+correctamente.&tipo=exito");
        exit;
    } else {
        header("Location: ?url=cliente/viewCliente&msg=No+se+pudo+actualizar+el+estado+del+cliente.&tipo=error");
        exit;
    }
  }

  // buscar clientes
  public function search() {
    if (isset($_GET['buscar'])) {
      $query = $_GET['buscar'];
      $clientes = $this->clienteModel->search($query);
      include 'views/clientes/viewCliente.php';
    } else {
      redirect('cliente', 'viewCliente');
    }
  }

  // buscar clientes por tipo
  public function filterByType() {
    if (isset($_GET['tipo'])) {
      $tipo = $_GET['tipo'];
      $clientes = $this->clienteModel->getByType($tipo);
      include 'views/clientes/viewCliente.php';
    } else {
      redirect('cliente', 'viewCliente');
    }
  }

  // contar clientes por tipo
  public function countByType() {
    if (isset($_GET['tipo'])) {
      $tipo = $_GET['tipo'];
      $count = $this->clienteModel->countByTipo($tipo);
      return $count;
    } else {
      return 0;
    }
  }

  // buscar clientes por estado
  public function filterByEstado() {
    if (isset($_GET['estado'])) {
      $estado = $_GET['estado'];
      $clientes = $this->clienteModel->filterByEstado($estado);
      include 'views/clientes/viewCliente.php';
    } else {
      redirect('cliente', 'viewCliente');
    }
  }

  // buscar clientes por tipo de documento
  public function filterByTipoDocumento() {
    if (isset($_GET['tipoDocumento'])) {
      $idTipoDocumento = $_GET['tipoDocumento'];
      $clientes = $this->clienteModel->filterByTipoDocumento($idTipoDocumento);
      include 'views/clientes/viewCliente.php';
    } else {
      redirect('cliente', 'viewCliente');
    }
  }
  


  
}
