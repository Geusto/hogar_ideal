<?php
require_once 'models/Propiedad.php';
require_once 'includes/functions.php';

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
        
        // Obtener fotos de portada para cada propiedad
        foreach ($propiedades as &$propiedad) {
            $fotoPortada = $this->propiedadModel->getFotoPortada($propiedad['id_propiedad']);
            $propiedad['foto_portada'] = $fotoPortada;
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
        
        // Obtener las fotos de la propiedad
        $fotos = $this->propiedadModel->getFotos($id);
        
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
        $resultado = $this->propiedadModel->delete($id);
        if ($resultado === 'ok') {
            redirect('propiedad', 'index', null, [
                'success' => 3,
                'msg' => 'Propiedad eliminada exitosamente.',
                'tipo' => 'exito'
            ]);
        } elseif ($resultado === 'venta') {
            redirect('propiedad', 'index', null, [
                'error' => 'delete_failed',
                'msg' => 'No se puede eliminar la propiedad porque tiene ventas asociadas.',
                'tipo' => 'error'
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
        
        // Obtener las fotos de la propiedad
        $fotos = $this->propiedadModel->getFotos($id);
        $fotoPortada = $this->propiedadModel->getFotoPortada($id);
        
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
    
    // Subir fotos para una propiedad
    public function uploadFotos($idPropiedad) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/FotoPropiedad.php';
            $fotoModel = new FotoPropiedad();
            
            $fotosSubidas = 0;
            $errores = [];
            
            // Verificar que se recibieron archivos
            if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
                redirect('propiedad', 'edit', $idPropiedad, [
                    'error' => 'no_files',
                    'msg' => 'No se seleccionaron archivos para subir.',
                    'tipo' => 'error'
                ]);
                return;
            }
            
            // Verificar que la carpeta uploads existe y tiene permisos
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Procesar cada foto subida
            $totalArchivos = count($_FILES['fotos']['name']);
            
            for ($i = 0; $i < $totalArchivos; $i++) {
                // Verificar que no hay errores en este archivo
                if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) {
                    $errores[] = "Error en archivo " . $_FILES['fotos']['name'][$i] . ": " . $this->getUploadErrorMessage($_FILES['fotos']['error'][$i]);
                    continue;
                }
                
                // Verificar que el archivo es válido
                if (!is_uploaded_file($_FILES['fotos']['tmp_name'][$i])) {
                    $errores[] = "Archivo no válido: " . $_FILES['fotos']['name'][$i];
                    continue;
                }
                
                $nombreArchivo = uniqid() . '_' . basename($_FILES['fotos']['name'][$i]);
                $rutaDestino = $uploadDir . $nombreArchivo;
                $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
                $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                // Verificar tipo de archivo
                if (!in_array($tipoArchivo, $tiposPermitidos)) {
                    $errores[] = "Tipo de archivo no permitido: " . $_FILES['fotos']['name'][$i] . " (solo JPG, PNG, GIF, WEBP)";
                    continue;
                }
                
                // Verificar tamaño del archivo (máximo 10MB)
                if ($_FILES['fotos']['size'][$i] > 10 * 1024 * 1024) {
                    $errores[] = "Archivo muy grande: " . $_FILES['fotos']['name'][$i] . " (máximo 10MB)";
                    continue;
                }
                
                // Mover archivo subido
                if (move_uploaded_file($_FILES['fotos']['tmp_name'][$i], $rutaDestino)) {
                    // Determinar si es la primera foto (portada)
                    $esPortada = ($fotosSubidas === 0) ? 1 : 0;
                    
                    // Obtener descripción si existe
                    $descripcion = '';
                    if (isset($_POST['descripciones']) && isset($_POST['descripciones'][$i])) {
                        $descripcion = trim($_POST['descripciones'][$i]);
                    }
                    
                    $data = [
                        'id_propiedad' => $idPropiedad,
                        'nombre_archivo' => $rutaDestino,
                        'descripcion' => $descripcion,
                        'orden' => $fotosSubidas + 1,
                        'es_portada' => $esPortada
                    ];
                    
                    // Guardar en la base de datos
                    if ($fotoModel->create($data)) {
                        $fotosSubidas++;
                    } else {
                        $errores[] = "Error al guardar en BD: " . $_FILES['fotos']['name'][$i];
                        // Eliminar archivo si no se pudo guardar en BD
                        if (file_exists($rutaDestino)) {
                            unlink($rutaDestino);
                        }
                    }
                } else {
                    $errores[] = "Error al mover archivo: " . $_FILES['fotos']['name'][$i];
                }
            }
            
            // Preparar mensaje de resultado
            if ($fotosSubidas > 0) {
                $mensaje = "Se subieron $fotosSubidas de $totalArchivos fotos exitosamente.";
                $tipo = 'exito';
                
                if (!empty($errores)) {
                    $mensaje .= " Errores: " . implode(', ', $errores);
                }
            } else {
                $mensaje = "No se pudo subir ninguna foto. Errores: " . implode(', ', $errores);
                $tipo = 'error';
            }
            
            redirect('propiedad', 'edit', $idPropiedad, [
                'success' => $fotosSubidas,
                'msg' => $mensaje,
                'tipo' => $tipo
            ]);
        }
    }
    
    // Función auxiliar para obtener mensajes de error de upload
    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "El archivo excede el tamaño máximo permitido por el servidor";
            case UPLOAD_ERR_FORM_SIZE:
                return "El archivo excede el tamaño máximo permitido por el formulario";
            case UPLOAD_ERR_PARTIAL:
                return "El archivo se subió parcialmente";
            case UPLOAD_ERR_NO_FILE:
                return "No se subió ningún archivo";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Falta la carpeta temporal";
            case UPLOAD_ERR_CANT_WRITE:
                return "Error al escribir el archivo en disco";
            case UPLOAD_ERR_EXTENSION:
                return "Una extensión de PHP detuvo la subida del archivo";
            default:
                return "Error desconocido";
        }
    }
    
    // Eliminar una foto
    public function deleteFoto($idFoto) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/FotoPropiedad.php';
            $fotoModel = new FotoPropiedad();
            
            // Debug: Verificar que se recibió el ID
            error_log("DEBUG: Intentando eliminar foto ID: " . $idFoto);
            
            $foto = $fotoModel->getById($idFoto);
            if ($foto) {
                error_log("DEBUG: Foto encontrada, eliminando archivo: " . $foto['nombre_archivo']);
                
                // Eliminar archivo físico
                if (file_exists($foto['nombre_archivo'])) {
                    unlink($foto['nombre_archivo']);
                    error_log("DEBUG: Archivo físico eliminado");
                }
                
                // Eliminar registro de la base de datos
                if ($fotoModel->delete($idFoto)) {
                    error_log("DEBUG: Registro eliminado de BD, redirigiendo a editar");
                    redirect('propiedad', 'edit', $foto['id_propiedad'], [
                        'success' => 1,
                        'msg' => 'Foto eliminada exitosamente.',
                        'tipo' => 'exito'
                    ]);
                } else {
                    error_log("DEBUG: Error al eliminar de BD");
                }
            } else {
                error_log("DEBUG: Foto no encontrada con ID: " . $idFoto);
            }
            
            redirect('propiedad', 'index', null, [
                'error' => 'delete_failed',
                'msg' => 'Error al eliminar la foto.',
                'tipo' => 'error'
            ]);
        }
    }
    
    // Cambiar foto de portada
    public function setPortada($idFoto) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/FotoPropiedad.php';
            $fotoModel = new FotoPropiedad();
            
            $foto = $fotoModel->getById($idFoto);
            if ($foto) {
                if ($fotoModel->setPortada($idFoto, $foto['id_propiedad'])) {
                    redirect('propiedad', 'edit', $foto['id_propiedad'], [
                        'success' => 1,
                        'msg' => 'Foto de portada actualizada exitosamente.',
                        'tipo' => 'exito'
                    ]);
                }
            }
            
            redirect('propiedad', 'index', null, [
                'error' => 'update_failed',
                'msg' => 'Error al actualizar la foto de portada.',
                'tipo' => 'error'
            ]);
        }
    }
    
    // Quitar portada de una foto
    public function quitarPortada($idFoto) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/FotoPropiedad.php';
            $fotoModel = new FotoPropiedad();
            
            $foto = $fotoModel->getById($idFoto);
            
            if ($foto) {
                if ($fotoModel->quitarPortada($idFoto, $foto['id_propiedad'])) {
                    redirect('propiedad', 'edit', $foto['id_propiedad'], [
                        'success' => 1,
                        'msg' => 'Portada quitada exitosamente.',
                        'tipo' => 'exito'
                    ]);
                }
            }
            
            redirect('propiedad', 'index', null, [
                'error' => 'update_failed',
                'msg' => 'Error al quitar la portada.',
                'tipo' => 'error'
            ]);
        }
    }
}
?> 