<?php
require_once 'models/Propiedad.php';
require_once 'models/Cliente.php';
require_once 'models/Agente.php';
require_once 'models/Venta.php';

class HomeController {
    
    public function index() {
        // Obtener estadísticas básicas usando los modelos
        try {
            // Instanciar todos los modelos
            $propiedadModel = new Propiedad();
            $clienteModel = new Cliente();
            $agenteModel = new Agente();
            $ventaModel = new Venta();
            
            // Usar métodos de los modelos para obtener estadísticas
            $propiedadesDisponibles = $propiedadModel->countByEstado('disponible');
            $totalClientes = $clienteModel->count();
            $totalAgentes = $agenteModel->count();
            $ventasMes = $ventaModel->countByMesActual();
            
        } catch(PDOException $e) {
            // Si hay error, establecer valores por defecto
            $propiedadesDisponibles = 0;
            $totalClientes = 0;
            $totalAgentes = 0;
            $ventasMes = 0;
        }
        
        // Pasar los datos a la vista
        include 'views/home/index.php';
    }
}
?> 