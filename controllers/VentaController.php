<?php
require_once 'models/Venta.php';
require_once 'vendor/autoload.php';

use Mpdf\Mpdf;

class VentaController {
  private $ventaModel;

  public function __construct() {
    $this->ventaModel = new Venta();
  }

  public function index(){
    $ventas = $this->ventaModel->getAll();
    include 'views/ventas/index.php';
  }

  public function comprobantePDF($id) {
    $venta = $this->ventaModel->getById($id);
    
    if (!$venta) {
      redirect('ventas', 'index', null, ['error' => 'Venta no encontrada']);
      return;
    }
    
    $this->generarComprobantePDF($venta);
  }

  public function comprobantePDFView($id) {
    $venta = $this->ventaModel->getById($id);
    
    if (!$venta) {
      redirect('ventas', 'index', null, ['error' => 'Venta no encontrada']);
      return;
    }
    
    $this->generarComprobantePDFView($venta);
  }

  private function generarComprobantePDF($venta) {
    
    try {
      // Crear instancia de mPDF
      $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
      ]);
      
      // Configurar metadatos
      $mpdf->SetTitle('Comprobante de Venta #' . $venta['id_venta']);
      $mpdf->SetAuthor('Sistema Hogar Ideal');
      $mpdf->SetCreator('Hogar Ideal');
      
      // Generar HTML del comprobante
      $html = $this->generarHTMLComprobante($venta);
      
      // Escribir HTML en el PDF
      $mpdf->WriteHTML($html);
      
      // Generar archivo
      $filename = 'comprobante_venta_' . str_pad($venta['id_venta'], 6, '0', STR_PAD_LEFT) . '.pdf';
      $mpdf->Output($filename, 'D');
      
    } catch (Exception $e) {
      redirect('ventas', 'index', null, ['error' => 'Error al generar PDF: ' . $e->getMessage()]);
    }
  }

  private function generarComprobantePDFView($venta) {
    try {
      // Crear instancia de mPDF
      $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
      ]);
      
      // Configurar metadatos
      $mpdf->SetTitle('Comprobante de Venta #' . $venta['id_venta']);
      $mpdf->SetAuthor('Sistema Hogar Ideal');
      $mpdf->SetCreator('Hogar Ideal');
      
      // Generar HTML del comprobante
      $html = $this->generarHTMLComprobante($venta);
      
      // Escribir HTML en el PDF
      $mpdf->WriteHTML($html);
      
      // Mostrar en navegador
      $filename = 'comprobante_venta_' . str_pad($venta['id_venta'], 6, '0', STR_PAD_LEFT) . '.pdf';
      $mpdf->Output($filename, 'I');
      
    } catch (Exception $e) {
      redirect('ventas', 'index', null, ['error' => 'Error al generar PDF: ' . $e->getMessage()]);
    }
  }

  private function generarHTMLComprobante($venta) {
    return '
    <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; color: #2563eb; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; }
        .numero-venta { font-size: 18px; color: #666; margin: 10px 0; }
        .fecha { font-size: 14px; color: #888; }
        .seccion { margin: 15px 0; }
        .titulo { font-size: 16px; font-weight: bold; color: #2563eb; margin-bottom: 10px; border-left: 4px solid #2563eb; padding-left: 10px; }
        .dato { margin: 8px 0; }
        .etiqueta { font-weight: bold; color: #374151; }
        .valor { color: #111827; }
        .total { background-color: #f3f4f6; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .total-label { font-size: 16px; font-weight: bold; color: #2563eb; }
        .total-valor { font-size: 20px; font-weight: bold; color: #059669; }
        .footer { text-align: center; font-size: 11px; color: #6b7280; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .emoji-img { width: 18px; height: 18px; vertical-align: middle; margin: 0 3px; }
        .emoji-large { width: 25px; height: 25px; }
        .info-box { background-color: #f9fafb; padding: 10px; border-radius: 5px; margin: 10px 0; }
      </style>
    </head>
    <body>
      <div class="header">
        <div class="logo">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/1f3e0.svg" class="emoji-img emoji-large"> Hogar Ideal
        </div>
        <div class="numero-venta">Comprobante de Venta #' . str_pad($venta['id_venta'], 6, '0', STR_PAD_LEFT) . '</div>
        <div class="fecha">Fecha: ' . date('d/m/Y', strtotime($venta['fecha_venta'])) . '</div>
      </div>
      
      <div class="seccion">
        <div class="titulo">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/1f4cb.svg" class="emoji-img"> Información de la Propiedad
        </div>
        <div class="info-box">
          <div class="dato">
            <span class="etiqueta">Dirección:</span>
            <span class="valor">' . htmlspecialchars($venta['propiedad_direccion']) . '</span>
          </div>
          <div class="dato">
            <span class="etiqueta">Tipo:</span>
            <span class="valor">' . ucfirst($venta['propiedad_tipo'] ?? 'No especificado') . '</span>
          </div>
        </div>
      </div>
      
      <div class="seccion">
        <div class="titulo">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/1f465.svg" class="emoji-img"> Partes Intervinientes
        </div>
        <div class="info-box">
          <div class="dato">
            <span class="etiqueta">Vendedor:</span>
            <span class="valor">' . htmlspecialchars($venta['cliente_vendedor']) . '</span>
          </div>
          <div class="dato">
            <span class="etiqueta">Comprador:</span>
            <span class="valor">' . htmlspecialchars($venta['cliente_comprador']) . '</span>
          </div>
          <div class="dato">
            <span class="etiqueta">Agente Inmobiliario:</span>
            <span class="valor">' . htmlspecialchars($venta['agente_nombre']) . '</span>
          </div>
          <div class="dato">
            <span class="etiqueta">Método de Pago:</span>
            <span class="valor">' . ucfirst($venta['metodo_pago']) . '</span>
          </div>
        </div>
      </div>
      
      <div class="seccion">
        <div class="titulo">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/1f4b0.svg" class="emoji-img"> Detalles Financieros
        </div>
        <div class="info-box">
          <div class="dato">
            <span class="etiqueta">Precio de Venta:</span>
            <span class="valor">$' . number_format($venta['precio_final'], 0, ',', '.') . '</span>
          </div>
          <div class="dato">
            <span class="etiqueta">Comisión del Agente:</span>
            <span class="valor">$' . number_format($venta['comision'], 0, ',', '.') . '</span>
          </div>
        </div>
      </div>
      
      <div class="total">
        <div class="total-label">Precio Final de la Venta:</div>
        <div class="total-valor">$' . number_format($venta['precio_final'], 0, ',', '.') . '</div>
      </div>
      
      <div class="footer">
        <p>Este documento es un comprobante oficial de la transacción realizada.</p>
        <p>Generado el ' . date('d/m/Y H:i:s') . ' por el sistema Hogar Ideal.</p>
        <p>Para consultas, contacte a su agente inmobiliario.</p>
      </div>
    </body>
    </html>
    ';
  }

}
