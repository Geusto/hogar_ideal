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

  // Obtener un cliente por ID
  public function getById($id){
    $stmt = this->pdo->prepare("SELECT * FROM agente WHERE id_agente = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Mostrar formulario para editar agente
  public function edit($id) {
    $agente = $this->agenteModel->getById($id);
    
    if (!$agente) {
      redirect('agente', 'index');
    }
    
    include 'views/agentes/edit.php';
  }

  // Actualizar agente
  public function update($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'zona_asignada' => $_POST['zona_asignada'] ?? '',
            'activo' => $_POST['activo'] ?? '',
        ];

        // Obtener la imagen actual
        $agenteActual = $this->agenteModel->getById($id);
        $data['imagen_perfil'] = $agenteActual['imagen_perfil'] ?? null;

        // Procesar imagen de perfil si se sube una nueva
        if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
          $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
          $rutaDestino = 'uploads/' . $nombreArchivo;
          $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
          $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
          if (in_array($tipoArchivo, $tiposPermitidos)) {
            if (move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaDestino)) {
              $data['imagen_perfil'] = $rutaDestino;
            }
          }
        }

        // Validar que el email no esté en uso
        if ($this->agenteModel->emailEnUso($data['email'], $id)) {
          redirect('agente', 'edit', $id, ['msg' => 'El email ya está en uso.', 'tipo' => 'error']);
        } 

        // Validar que el teléfono no esté en uso
        if ($this->agenteModel->telefonoEnUso($data['telefono'], $id)) {
          redirect('agente', 'edit', $id, ['msg' => 'El teléfono ya está en uso.', 'tipo' => 'error']);
        }

        if ($this->agenteModel->update($id, $data)) {
          redirect('agente', 'index', null, ['msg' => 'Agente actualizado correctamente.', 'tipo' => 'exito']);
        } else {
          redirect('agente', 'edit', $id, ['msg' => 'No se pudo actualizar el agente.', 'tipo' => 'eliminado']);
        }
    }
  }

  // Mostrar formulario para crear nuevo agente
  public function create() {
    include 'views/agentes/create.php';
  }

  // Guardar agente
  public function store() {
    $data = [
      'nombre_completo' => $_POST['nombre_completo'] ?? '',
      'telefono' => $_POST['telefono'] ?? '',
      'email' => $_POST['email'] ?? '',
      'zona_asignada' => $_POST['zona_asignada'] ?? '',
      'activo' => $_POST['activo'] ?? '1',
    ];

    // Validar que el email no esté en uso
    if ($this->agenteModel->emailEnUso($data['email'])) {
      redirect('agente', 'create', null, ['msg' => 'El email ya está en uso.', 'tipo' => 'error']);
    }
    // Validar que el teléfono no esté en uso
    if ($this->agenteModel->telefonoEnUso($data['telefono'])) {
      redirect('agente', 'create', null, ['msg' => 'El teléfono ya está en uso.', 'tipo' => 'error']);
    }

    // Procesar imagen de perfil
    if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
      $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
      $rutaDestino = 'uploads/' . $nombreArchivo;
      $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
      $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
      if (in_array($tipoArchivo, $tiposPermitidos)) {
          if (move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaDestino)) {
              $data['imagen_perfil'] = $rutaDestino;
          } else {
              $data['imagen_perfil'] = null;
          }
      } else {
        $data['imagen_perfil'] = null;
      }
    } else {
      $data['imagen_perfil'] = null;
    }

    if ($this->agenteModel->create($data)) {
      redirect('agente', 'index', null, ['msg' => 'Agente creado correctamente.', 'tipo' => 'exito']);
    } else {
      redirect('agente', 'create', null, ['msg' => 'No se pudo crear el agente.', 'tipo' => 'error']);
    }
  }

  // Eliminar agente
  public function delete($id) {
    $resultado = $this->agenteModel->eliminarOInactivar($id);
    if ($resultado === 'eliminado') {
      redirect('agente', 'index', null, ['msg' => 'Agente eliminado correctamente.', 'tipo' => 'eliminado']);
    } else if ($resultado === 'inactivado') {
      redirect('agente', 'index', null, ['msg' => 'El agente tiene propiedades asociadas y fue marcado como inactivo.', 'tipo' => 'advertencia']);
    } else {
      redirect('agente', 'index', null, ['msg' => 'No se pudo eliminar el agente.', 'tipo' => 'eliminado']);
    }
  }
}