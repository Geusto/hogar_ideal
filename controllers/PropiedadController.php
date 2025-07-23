<?php
require_once 'models/Propiedad.php';

class PropiedadController {
    private $propiedadModel;
    
    public function __construct() {
        $this->propiedadModel = new Propiedad();
    }
    
    // Mostrar lista de propiedades
    public function index() {
        // Verificar si hay filtros aplicados
        $estado = $_GET['estado'] ?? '';
        $tipo_propiedad = $_GET['tipo_propiedad'] ?? '';
        
        if (!empty($estado)) {
            $propiedades = $this->propiedadModel->getByEstado($estado);
        } elseif (!empty($tipo_propiedad)) {
            $propiedades = $this->propiedadModel->getByTipo($tipo_propiedad);
        } else {
            $propiedades = $this->propiedadModel->getAll();
        }
        
        include 'views/propiedades/index.php';
    }
    
    // Mostrar formulario para crear nueva propiedad
    public function create() {
        $agentes = $this->propiedadModel->getAgentes();
        $clientesVendedores = $this->propiedadModel->getClientesVendedores();
        include 'views/propiedades/create.php';
    }
    
    // Guardar nueva propiedad
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo' => $_POST['tipo'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'habitaciones' => $_POST['habitaciones'] ?? 0,
                'banos' => $_POST['banos'] ?? 0,
                'superficie' => $_POST['superficie'] ?? 0,
                'precio' => $_POST['precio'] ?? 0,
                'estado' => $_POST['estado'] ?? 'disponible',
                'id_cliente_vendedor' => $_POST['id_cliente_vendedor'] ?? 1,
                'id_agente' => $_POST['id_agente'] ?? 1
            ];

            // Procesar imagen de portada
            if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = uniqid() . '_' . basename($_FILES['portada']['name']);
                $rutaDestino = 'uploads/' . $nombreArchivo;
                $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
                $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($tipoArchivo, $tiposPermitidos)) {
                    if (move_uploaded_file($_FILES['portada']['tmp_name'], $rutaDestino)) {
                        $data['portada'] = $rutaDestino;
                    } else {
                        $data['portada'] = null;
                    }
                } else {
                    $data['portada'] = null;
                }
            } else {
                $data['portada'] = null;
            }

            if ($this->propiedadModel->create($data)) {
                redirect('propiedad', 'index', null, [
                    'success' => 1,
                    'msg' => 'Propiedad creada exitosamente.',
                    'tipo' => 'exito'
                ]);
            } else {
                redirect('propiedad', 'create', null, [
                    'error' => 'create_failed',
                    'msg' => 'Error al crear la propiedad.',
                    'tipo' => 'error'
                ]);
            }
        }
    }
    
    // Mostrar formulario para editar propiedad
    public function edit($id) {
        $propiedad = $this->propiedadModel->getById($id);
        $agentes = $this->propiedadModel->getAgentes();
        $clientesVendedores = $this->propiedadModel->getClientesVendedores();
        
        if (!$propiedad) {
            redirect('propiedad', 'index');
        }
        
        include 'views/propiedades/edit.php';
    }
    
    // Actualizar propiedad
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tipo' => $_POST['tipo'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'habitaciones' => $_POST['habitaciones'] ?? 0,
                'banos' => $_POST['banos'] ?? 0,
                'superficie' => $_POST['superficie'] ?? 0,
                'precio' => $_POST['precio'] ?? 0,
                'estado' => $_POST['estado'] ?? 'disponible',
                'id_cliente_vendedor' => $_POST['id_cliente_vendedor'] ?? 1,
                'id_agente' => $_POST['id_agente'] ?? 1
            ];

            // Obtener la portada actual
            $propiedadActual = $this->propiedadModel->getById($id);
            $data['portada'] = $propiedadActual['portada'] ?? null;

            // Procesar imagen de portada si se sube una nueva
            if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = uniqid() . '_' . basename($_FILES['portada']['name']);
                $rutaDestino = 'uploads/' . $nombreArchivo;
                $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
                $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($tipoArchivo, $tiposPermitidos)) {
                    if (move_uploaded_file($_FILES['portada']['tmp_name'], $rutaDestino)) {
                        $data['portada'] = $rutaDestino;
                    }
                }
            }

            if ($this->propiedadModel->update($id, $data)) {
                redirect('propiedad', 'index', null, [
                    'success' => 2,
                    'msg' => 'Propiedad actualizada exitosamente.',
                    'tipo' => 'exito'
                ]);
            } else {
                redirect('propiedad', 'edit', $id, [
                    'error' => 'update_failed',
                    'msg' => 'Error al actualizar la propiedad.',
                    'tipo' => 'error'
                ]);
            }
        }
    }
    
    // Eliminar propiedad
    public function delete($id) {
        if ($this->propiedadModel->delete($id)) {
            redirect('propiedad', 'index', null, [
                'success' => 3,
                'msg' => 'Propiedad eliminada exitosamente.',
                'tipo' => 'exito'
            ]);
        } else {
            redirect('propiedad', 'index', null, [
                'error' => 'delete_failed',
                'msg' => 'Error al eliminar la propiedad.',
                'tipo' => 'error'
            ]);
        }
    }
    
    // Mostrar detalles de una propiedad
    public function show($id) {
        $propiedad = $this->propiedadModel->getById($id);
        
        if (!$propiedad) {
            redirect('propiedad', 'index');
        }
        
        include 'views/propiedades/show.php';
    }
    
    // Buscar propiedades
    public function search() {
        $query = $_GET['q'] ?? '';
        $propiedades = [];
        
        if (!empty($query)) {
            $propiedades = $this->propiedadModel->search($query);
        }
        
        include 'views/propiedades/index.php';
    }
    
    // Filtrar por estado (método legacy, ahora se maneja en index)
    public function filter() {
        $estado = $_GET['estado'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        
        if (!empty($estado)) {
            $propiedades = $this->propiedadModel->getByEstado($estado);
        } elseif (!empty($tipo)) {
            $propiedades = $this->propiedadModel->getByTipo($tipo);
        } else {
            $propiedades = $this->propiedadModel->getAll();
        }
        
        include 'views/propiedades/index.php';
    }
}
?> 