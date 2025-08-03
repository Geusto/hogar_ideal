<?php
require_once 'config/database.php';

class Venta {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    // Obtener todas las ventas
    public function getAll() {
        $stmt = $this->pdo->query("SELECT v.id_venta, v.fecha_venta, v.precio_final, v.comision, v.metodo_pago, p.direccion as propiedad_direccion, c.nombre_completo as cliente_comprador, c2.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM venta v 
        LEFT JOIN propiedad p ON v.id_propiedad = p.id_propiedad 
        LEFT JOIN cliente c ON v.id_cliente_comprador = c.id_cliente 
        LEFT JOIN cliente c2 ON v.id_cliente_vendedor = c2.id_cliente 
        LEFT JOIN agente a ON v.id_agente = a.id_agente 
        ORDER BY v.fecha_venta DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener una venta por ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT v.*, p.direccion as propiedad_direccion, c.nombre_completo as cliente_comprador, a.nombre_completo as agente_nombre 
        FROM venta v 
        LEFT JOIN propiedad p ON v.id_propiedad = p.id_propiedad 
        LEFT JOIN cliente c ON v.id_cliente_comprador = c.id_cliente 
        LEFT JOIN agente a ON v.id_agente = a.id_agente 
        WHERE v.id_venta = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Crear nueva venta
    public function create($data) {
        $sql = "INSERT INTO venta (id_propiedad, id_cliente_comprador, id_agente, precio_venta, fecha_venta) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_propiedad'],
            $data['id_cliente_comprador'],
            $data['id_agente'],
            $data['precio_venta'],
            $data['fecha_venta']
        ]);
    }
    
    // Actualizar venta
    public function update($id, $data) {
        $sql = "UPDATE venta SET id_propiedad = ?, id_cliente_comprador = ?, id_agente = ?, precio_venta = ?, fecha_venta = ? WHERE id_venta = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['id_propiedad'],
            $data['id_cliente_comprador'],
            $data['id_agente'],
            $data['precio_venta'],
            $data['fecha_venta'],
            $id
        ]);
    }
    
    // Eliminar venta
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM venta WHERE id_venta = ?");
        return $stmt->execute([$id]);
    }
    
    // Contar total de ventas
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM venta");
        return $stmt->fetch()['total'];
    }
    
    // Contar ventas del mes actual
    public function countByMesActual() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM venta WHERE MONTH(fecha_venta) = MONTH(CURRENT_DATE()) AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())");
        return $stmt->fetch()['total'];
    }
    
    // Contar ventas por año
    public function countByAnio($anio) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM venta WHERE YEAR(fecha_venta) = ?");
        $stmt->execute([$anio]);
        return $stmt->fetch()['total'];
    }
    
    // Obtener ventas por rango de fechas
    public function getByRangoFechas($fechaInicio, $fechaFin) {
        $stmt = $this->pdo->prepare("SELECT v.*, p.direccion as propiedad_direccion, c.nombre_completo as cliente_comprador, a.nombre_completo as agente_nombre 
        FROM venta v 
        LEFT JOIN propiedad p ON v.id_propiedad = p.id_propiedad 
        LEFT JOIN cliente c ON v.id_cliente_comprador = c.id_cliente 
        LEFT JOIN agente a ON v.id_agente = a.id_agente 
        WHERE v.fecha_venta BETWEEN ? AND ? ORDER BY v.fecha_venta DESC");
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener propiedades para el formulario
    public function getPropiedadesDisponibles() {
        $stmt = $this->pdo->query("SELECT id_propiedad, direccion FROM propiedad WHERE estado = 'disponible' ORDER BY direccion");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener clientes compradores para el formulario
    public function getClientesCompradores() {
        $stmt = $this->pdo->query("SELECT id_cliente, nombre_completo FROM cliente WHERE tipo IN ('Comprador', 'Ambos') ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener agentes para el formulario
    public function getAgentes() {
        $stmt = $this->pdo->query("SELECT id_agente, nombre_completo FROM agente ORDER BY nombre_completo");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 