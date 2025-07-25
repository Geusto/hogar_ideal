<?php
require_once 'config/database.php';

class Agente {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todos los agentes
    public function getAll() {
        $sql = "SELECT a.*, td.descripcion AS tipo_documento_nombre
                FROM agente a
                LEFT JOIN tipo_documento td ON a.tipo_documento = td.idTipoDocumento
                ORDER BY a.nombre_completo";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener un agente por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM agente WHERE id_agente = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nuevo agente
    public function create($data) {
        $sql = "INSERT INTO agente (nombre_completo, email, telefono, zona_asignada, tipo_documento, documento, activo, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['zona_asignada'],
            $data['tipo_documento'],
            $data['documento'],
            $data['activo'],
            $data['imagen_perfil']
        ]);
    }
    
    // Actualizar agente
    public function update($id, $data) {
        $sql = "UPDATE agente SET nombre_completo = ?, email = ?, telefono = ?, zona_asignada = ?, tipo_documento = ?, documento = ?, activo = ?, imagen_perfil = ? WHERE id_agente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre_completo'],
            $data['email'],
            $data['telefono'],
            $data['zona_asignada'],
            $data['tipo_documento'],
            $data['documento'],
            $data['activo'],
            $data['imagen_perfil'],
            $id
        ]);
    }
    
    // Eliminar agente
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM agente WHERE id_agente = ?");
        return $stmt->execute([$id]);
    }
    
    // Eliminar o inactivar agente según relaciones
    public function eliminarOInactivar($id) {
        // Verificar si el agente tiene propiedades asociadas
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM propiedad WHERE id_agente = ?");
        $stmt->execute([$id]);
        $total = $stmt->fetch()['total'];
        if ($total > 0) {
            // Marcar como inactivo
            $stmt = $this->pdo->prepare("UPDATE agente SET activo = 0 WHERE id_agente = ?");
            $stmt->execute([$id]);
            return 'inactivado';
        } else {
            // Eliminar físicamente
            $stmt = $this->pdo->prepare("DELETE FROM agente WHERE id_agente = ?");
            $stmt->execute([$id]);
            return 'eliminado';
        }
    }
    
    // Contar total de agentes
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM agente");
        return $stmt->fetch()['total'];
    }
    
    // Contar agentes por zona asignada
    public function countByZonaAsignada($zona_asignada) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM agente WHERE zona_asignada = ?");
        $stmt->execute([$zona_asignada]);
        return $stmt->fetch()['total'];
    }
    
    // Buscar agentes
    public function search($query) {
        $sql = "SELECT * FROM agente WHERE nombre_completo LIKE ? OR email LIKE ? OR zona_asignada LIKE ? ORDER BY nombre_completo";
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar si el email está en uso (opcionalmente excluyendo un id)
    public function emailEnUso($email, $id = null) {
        $sql = "SELECT COUNT(*) as total FROM agente WHERE email = ?";
        $params = [$email];
        if ($id !== null) {
            $sql .= " AND id_agente != ?";
            $params[] = $id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'] > 0;
    }

    // Verificar si el teléfono está en uso (opcionalmente excluyendo un id)
    public function telefonoEnUso($telefono, $id = null) {
        $sql = "SELECT COUNT(*) as total FROM agente WHERE telefono = ?";
        $params = [$telefono];
        if ($id !== null) {
            $sql .= " AND id_agente != ?";
            $params[] = $id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'] > 0;
    }

    // Verificar si el documento y tipo_documento están en uso (opcionalmente excluyendo un id)
    public function documentoEnUso($documento, $tipo_documento, $id = null) {
        $sql = "SELECT COUNT(*) as total FROM agente WHERE documento = ? AND tipo_documento = ?";
        $params = [$documento, $tipo_documento];
        if ($id !== null) {
            $sql .= " AND id_agente != ?";
            $params[] = $id;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch()['total'] > 0;
    }

    // Obtener todos los tipos de documento
    public function getTiposDocumento() {
        $stmt = $this->pdo->query("SELECT * FROM tipo_documento");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 

