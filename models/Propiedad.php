<?php
require_once 'config/database.php';

class Propiedad {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todas las propiedades
    public function getAll() {
        $stmt = $this->pdo->query("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM propiedad p 
        LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
        LEFT JOIN agente a ON p.id_agente = a.id_agente 
        ORDER BY p.id_propiedad DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener una propiedad por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM propiedad p 
        LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
        LEFT JOIN agente a ON p.id_agente = a.id_agente 
        WHERE p.id_propiedad = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva propiedad
    public function create($data) {
        $sql = "INSERT INTO propiedad (tipo, direccion, habitaciones, banos, superficie, precio, estado, portada, id_cliente_vendedor, id_agente) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['tipo'],
            $data['direccion'],
            $data['habitaciones'],
            $data['banos'],
            $data['superficie'],
            $data['precio'],
            $data['estado'],
            $data['portada'],
            $data['id_cliente_vendedor'],
            $data['id_agente']
        ]);
    }
    
    // Actualizar propiedad
    public function update($id, $data) {
        $sql = "UPDATE propiedad SET 
                tipo = ?, direccion = ?, habitaciones = ?, banos = ?, 
                superficie = ?, precio = ?, estado = ?, portada = ?, id_cliente_vendedor = ?, id_agente = ? 
                WHERE id_propiedad = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['tipo'],
            $data['direccion'],
            $data['habitaciones'],
            $data['banos'],
            $data['superficie'],
            $data['precio'],
            $data['estado'],
            $data['portada'],
            $data['id_cliente_vendedor'],
            $data['id_agente'],
            $id
        ]);
    }
    
    // Verificar si la propiedad tiene ventas asociadas
    public function tieneVentasAsociadas($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM venta WHERE id_propiedad = ?");
        $stmt->execute([$id]);
        return $stmt->fetch()['total'] > 0;
    }

    // Eliminar propiedad solo si no tiene ventas asociadas
    public function delete($id) {
        if ($this->tieneVentasAsociadas($id)) {
            return 'venta'; // No eliminar, tiene ventas asociadas
        }
        $stmt = $this->pdo->prepare("DELETE FROM propiedad WHERE id_propiedad = ?");
        $stmt->execute([$id]);
        return 'ok';
    }
    
    // Obtener propiedades por estado
    public function getByEstado($estado) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
            FROM propiedad p 
            LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
            LEFT JOIN agente a ON p.id_agente = a.id_agente 
            WHERE p.estado = ? ORDER BY p.id_propiedad DESC");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener propiedades por tipo
    public function getByTipo($tipo) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
            FROM propiedad p 
            LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
            LEFT JOIN agente a ON p.id_agente = a.id_agente 
            WHERE p.tipo = ? ORDER BY p.id_propiedad DESC");
        $stmt->execute([$tipo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar propiedades
    public function search($query) {
        $sql = "SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
                FROM propiedad p 
                LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
                LEFT JOIN agente a ON p.id_agente = a.id_agente 
                WHERE p.direccion LIKE ? OR c.nombre_completo LIKE ? OR a.nombre_completo LIKE ?
                ORDER BY p.id_propiedad DESC";
        
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener agentes para el formulario
    public function getAgentes() {
        $stmt = $this->pdo->query("SELECT id_agente, nombre_completo FROM agente ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener clientes vendedores para el formulario
    public function getClientesVendedores() {
        $stmt = $this->pdo->query("SELECT id_cliente, nombre_completo FROM cliente WHERE tipo IN ('Vendedor', 'Ambos') ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Contar propiedades por estado
    public function countByEstado($estado) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM propiedad WHERE estado = ?");
        $stmt->execute([$estado]);
        return $stmt->fetch()['total'];
    }
}
?> 