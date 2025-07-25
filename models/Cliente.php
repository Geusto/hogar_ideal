<?php
require_once 'config/database.php';

class Cliente {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todos los clientes
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cliente ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener un cliente por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener clientes por tipo
    public function getByTipo($tipo) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE tipo = ? ORDER BY nombre_completo");
        $stmt->execute([$tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // obtener clientes por tipodocumento
    public function getByTipoDocumento($idTipoDocumento) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE idTipoDocumento = ? ORDER BY nombre_completo");
        $stmt->execute([$idTipoDocumento]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ver tipos de documento
    public function getTiposDocumento() {
        $stmt = $this->pdo->query("SELECT * FROM tipo_documento ORDER BY descripcion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener clientes por estado
    public function getByEstado($estado) {  
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE statusC = ? ORDER BY nombre_completo");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar cliente por nombre
    public function searchByName($name) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE nombre_completo LIKE ? ORDER BY nombre_completo");
        $stmt->execute(['%' . $name . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Crear nuevo cliente
    public function create($data) {
        
        $tiposPermitidos = ['Comprador', 'Vendedor', 'Ambos'];
        if (!in_array($data['tipo'], $tiposPermitidos)) {
            throw new Exception("Tipo de cliente no permitido: " . $data['tipo']);
        }

        // Conversión de estado numérico a texto
        if (isset($data['statusC'])) {
            $data['statusC'] = $data['statusC'] == 1 ? 1 : 0;
        } else {
            $data['statusC'] = 1; // estado por defecto: activo
        }

        $sql = "INSERT INTO cliente (nombre_completo, documento, direccion, telefono, email, tipo, idTipoDocumento, statusC) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        // Asegurarse de que todos los campos requeridos estén presentes
        return $stmt->execute([
            $data['nombre_completo'],
            $data['documento'],
            $data['direccion'],
            $data['telefono'],
            $data['email'],
            $data['tipo'],
            $data['idTipoDocumento'],
            $data['statusC']
        ]);
    }

    // Actualizar estado del cliente
    public function updateStatus($id, $status) {
        $statusInt = $status == 1 ? 1 : 0;
        $stmt = $this->pdo->prepare("UPDATE cliente SET statusC = ? WHERE id_cliente = ?");
        return $stmt->execute([$statusInt, $id]);
    }
    
    // Actualizar cliente
    public function update($id, $data) {

        $tiposPermitidos = ['Comprador', 'Vendedor', 'Ambos'];
        if (!in_array($data['tipo'], $tiposPermitidos)) {
            throw new Exception("Tipo de cliente no permitido: " . $data['tipo']);
        }

        $sql = "UPDATE cliente SET nombre_completo = ?, documento = ?, direccion = ?, telefono = ?, email = ?, tipo = ? WHERE id_cliente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['documento'],
            $data['direccion'],
            $data['telefono'],
            $data['email'],
            $data['tipo'],
            $id
        ]);
    }
    
    // Eliminar cliente
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cliente WHERE id_cliente = ?");
        return $stmt->execute([$id]);
    }
    
    // Contar total de clientes
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM cliente");
        return $stmt->fetch()['total'];
    }
    
    // Contar clientes por tipo
    public function countByTipo($tipo) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM cliente WHERE tipo = ?");
        $stmt->execute([$tipo]);
        return $stmt->fetch()['total'];
    }

    // buscar clientes por tipo
    public function filterByTipo($tipo) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE tipo = ? ORDER BY nombre_completo");
        $stmt->execute([$tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar clientes
    public function search($query) {
        $sql = "SELECT * FROM cliente WHERE nombre_completo LIKE ? OR email LIKE ? ORDER BY nombre_completo";
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // buscar clientes por estado
    public function filterByEstado($estado) {
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE statusC = ? ORDER BY nombre_completo");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // buscar clientes por tipo de documento
    public function filterByTipoDocumento($idTipoDocumento) {   
        $stmt = $this->pdo->prepare("SELECT * FROM cliente WHERE idTipoDocumento = ? ORDER BY nombre_completo");
        $stmt->execute([$idTipoDocumento]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar si el email ya está en uso
    public function emailEnUso($email, $id = null) {
        $sql = "SELECT COUNT(*) as total FROM cliente WHERE email = ?";
        $params = [$email];
        if ($id) {
            $sql .= " AND id_cliente != ?";
            $params[] = $id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'] > 0;
    }

    public function documentoEnUso($documento, $id = null) {
        $sql = "SELECT COUNT(*) as total FROM cliente WHERE documento = ?";
        $params = [$documento];
        if ($id) {
            $sql .= " AND id_cliente != ?";
            $params[] = $id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'] > 0;
    }

    
    
    // Verificar si el cliente está activo
    public function isActive($id) {
        $stmt = $this->pdo->prepare("SELECT statusC FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['statusC'] == 1;
    }

    public function isInactive($id) {
        $stmt = $this->pdo->prepare("SELECT statusC FROM cliente WHERE id_cliente = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['statusC'] == 0;
    }

}
?> 