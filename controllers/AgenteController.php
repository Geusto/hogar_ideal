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

        if ($this->agenteModel->update($id, $data)) {
          redirect('agente', 'index', null, ['msg' => 'Agente actualizado correctamente.', 'tipo' => 'exito']);
        } else {
          redirect('agente', 'edit', $id, ['msg' => 'No se pudo actualizar el agente.', 'tipo' => 'eliminado']);
        }
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