<?php 
$title = 'Editar Propiedad - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Editar Propiedad</h1>
            <p class="text-gray-600">Modifica la información de la propiedad</p>
        </div>
        <a href="<?= prettyUrl('propiedad', 'index') ?>" 
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="<?= prettyUrl('propiedad', 'update', $propiedad['id_propiedad']) ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información básica -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Básica</h3>
                </div>
                
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Propiedad *</label>
                    <select id="tipo" name="tipo" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar tipo</option>
                        <option value="casa" <?php echo $propiedad['tipo'] === 'casa' ? 'selected' : ''; ?>>Casa</option>
                        <option value="apartamento" <?php echo $propiedad['tipo'] === 'apartamento' ? 'selected' : ''; ?>>Apartamento</option>
                        <option value="terreno" <?php echo $propiedad['tipo'] === 'terreno' ? 'selected' : ''; ?>>Terreno</option>
                        <option value="local" <?php echo $propiedad['tipo'] === 'local' ? 'selected' : ''; ?>>Local Comercial</option>
                    </select>
                </div>
                
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select id="estado" name="estado" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="disponible" <?php echo $propiedad['estado'] === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                        <option value="vendida" <?php echo $propiedad['estado'] === 'vendida' ? 'selected' : ''; ?>>Vendida</option>
                    </select>
                </div>
                
                <!-- Características -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Características</h3>
                </div>
                
                <div>
                    <label for="habitaciones" class="block text-sm font-medium text-gray-700 mb-2">Habitaciones</label>
                    <input type="number" id="habitaciones" name="habitaciones" min="0" 
                        value="<?php echo $propiedad['habitaciones']; ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="banos" class="block text-sm font-medium text-gray-700 mb-2">Baños</label>
                    <input type="number" id="banos" name="banos" min="0" 
                        value="<?php echo $propiedad['banos']; ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="superficie" class="block text-sm font-medium text-gray-700 mb-2">Superficie (m²) *</label>
                    <input type="number" id="superficie" name="superficie" min="0" step="0.01" required
                        value="<?php echo $propiedad['superficie']; ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-2">Precio ($) *</label>
                    <input type="number" id="precio" name="precio" min="0" step="0.01" required
                        value="<?php echo $propiedad['precio']; ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0.00">
                </div>
                
                <!-- Ubicación -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubicación</h3>
                </div>
                
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                    <input type="text" id="direccion" name="direccion" required
                        value="<?php echo htmlspecialchars($propiedad['direccion']); ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Calle, número, colonia">
                </div>
                
                <!-- Galería de Fotos -->
                <div class="md:col-span-2 mt-8 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Galería de Fotos</h3>
                    
                    <!-- Fotos existentes -->
                    <?php if (!empty($fotos)): ?>
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Fotos Actuales</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                <?php foreach ($fotos as $foto): ?>
                                    <div class="relative group">
                                        <img src="<?php echo assetUrl($foto['nombre_archivo']); ?>" 
                                            alt="<?php echo htmlspecialchars($foto['descripcion'] ?: 'Foto de la propiedad'); ?>" 
                                            class="w-full h-24 object-cover rounded-lg shadow-md">
                                        
                                        <!-- Indicador de portada -->
                                        <?php if ($foto['es_portada']): ?>
                                            <div class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                                Portada
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Acciones -->
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center space-x-2">
                                            <?php if (!$foto['es_portada']): ?>
                                                <form method="POST" action="<?= prettyUrl('propiedad', 'setPortada', $foto['id_foto']) ?>" class="inline">
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white p-1 rounded text-xs" title="Hacer portada">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <form method="POST" action="<?= prettyUrl('propiedad', 'deleteFoto', $foto['id_foto']) ?>" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta foto?')">
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-1 rounded text-xs" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Descripción -->
                                        <?php if (!empty($foto['descripcion'])): ?>
                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg">
                                                <?php echo htmlspecialchars($foto['descripcion']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Botón para abrir modal de fotos -->
                    <div class="border-t pt-6">
                        <button type="button" onclick="abrirModalFotos()" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            <i class="fas fa-images mr-2"></i>Gestionar Fotos
                        </button>
                    </div>
                </div>
                
                <!-- Relaciones -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Relaciones</h3>
                </div>
                
                <div>
                    <label for="id_cliente_vendedor" class="block text-sm font-medium text-gray-700 mb-2">Cliente Vendedor *</label>
                    <select id="id_cliente_vendedor" name="id_cliente_vendedor" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar cliente vendedor</option>
                        <?php foreach ($clientesVendedores as $cliente): ?>
                            <option value="<?php echo $cliente['id_cliente']; ?>" 
                                    <?php echo $propiedad['id_cliente_vendedor'] == $cliente['id_cliente'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cliente['nombre_completo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="id_agente" class="block text-sm font-medium text-gray-700 mb-2">Agente Responsable *</label>
                    <select id="id_agente" name="id_agente" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar agente</option>
                        <?php foreach ($agentes as $agente): ?>
                            <option value="<?php echo $agente['id_agente']; ?>" 
                                    <?php echo $propiedad['id_agente'] == $agente['id_agente'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($agente['nombre_completo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Información adicional -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Información del Sistema</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <strong>ID:</strong> <?php echo $propiedad['id_propiedad']; ?>
                    </div>
                    <div>
                        <strong>Cliente Vendedor:</strong> <?php echo htmlspecialchars($propiedad['cliente_vendedor'] ?? 'N/A'); ?>
                    </div>
                    <div>
                        <strong>Agente:</strong> <?php echo htmlspecialchars($propiedad['agente_nombre'] ?? 'N/A'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="<?= prettyUrl('propiedad', 'index') ?>" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i>Actualizar Propiedad
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para gestión de fotos -->
<div id="modalFotos" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-screen overflow-y-auto">
            <!-- Header del modal -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-800">Gestionar Fotos de la Propiedad</h3>
                <button onclick="cerrarModalFotos()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Contenido del modal -->
            <div class="p-6">
                
                <!-- Fotos existentes -->
                <?php if (!empty($fotos)): ?>
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Fotos Actuales (<?php echo count($fotos); ?> fotos)</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            <?php foreach ($fotos as $foto): ?>
                                <div class="relative group">
                                    <img src="<?php echo assetUrl($foto['nombre_archivo']); ?>" 
                                        alt="<?php echo htmlspecialchars($foto['descripcion'] ?: 'Foto de la propiedad'); ?>" 
                                        class="w-full h-24 object-cover rounded-lg shadow-md">
                                    
                                    <!-- Indicador de portada -->
                                    <?php if ($foto['es_portada']): ?>
                                        <div class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                            Portada
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Acciones -->
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center space-x-2">
                                        <?php if ($foto['es_portada']): ?>
                                            <!-- Si es portada, mostrar botón para quitar -->
                                            <button type="button" onclick="quitarPortada(<?php echo $foto['id_foto']; ?>)" 
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded text-sm" 
                                                    title="Quitar portada">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        <?php else: ?>
                                            <!-- Si no es portada, mostrar botón para hacer portada -->
                                            <button type="button" onclick="cambiarPortada(<?php echo $foto['id_foto']; ?>)" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded text-sm" 
                                                    title="Hacer portada">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <button type="button" onclick="eliminarFoto(<?php echo $foto['id_foto']; ?>)" 
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded text-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Indicadores siempre visibles -->
                                    <div class="absolute top-1 right-1 flex space-x-1">
                                        <?php if ($foto['es_portada']): ?>
                                            <!-- Si es portada, mostrar botón para quitar -->
                                            <button type="button" onclick="quitarPortada(<?php echo $foto['id_foto']; ?>)" 
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white p-1 rounded-full text-xs" 
                                                    title="Quitar portada">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        <?php else: ?>
                                            <!-- Si no es portada, mostrar botón para hacer portada -->
                                            <button type="button" onclick="cambiarPortada(<?php echo $foto['id_foto']; ?>)" 
                                                    class="bg-blue-500 hover:bg-blue-600 text-white p-1 rounded-full text-xs" 
                                                    title="Hacer portada">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Descripción -->
                                    <?php if (!empty($foto['descripcion'])): ?>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg">
                                            <?php echo htmlspecialchars($foto['descripcion']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg text-center">
                        <i class="fas fa-images text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Esta propiedad aún no tiene fotos</p>
                        <p class="text-sm text-gray-500 mt-2">Sube algunas fotos para empezar a crear tu galería</p>
                    </div>
                <?php endif; ?>
                
                <!-- Formulario para subir nuevas fotos -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Agregar Nuevas Fotos</h4>
                    <form method="POST" action="<?= prettyUrl('propiedad', 'uploadFotos', $propiedad['id_propiedad']) ?>" enctype="multipart/form-data" id="uploadForm">
                        <div class="space-y-6">
                            <!-- Selector de archivos -->
                            <div>
                                <label for="fotos" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Fotos</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                    <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple 
                                        class="hidden" onchange="mostrarArchivosSeleccionados()">
                                    <label for="fotos" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                        <div class="text-lg font-medium text-gray-700 mb-2">Haz clic para seleccionar fotos</div>
                                        <div class="text-sm text-gray-500">o arrastra y suelta aquí</div>
                                        <div class="text-xs text-gray-400 mt-2">Formatos: JPG, PNG, GIF, WEBP</div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Vista previa de archivos seleccionados -->
                            <div id="archivosSeleccionados" class="hidden">
                                <h5 class="text-md font-medium text-gray-700 mb-3">Archivos Seleccionados:</h5>
                                <div id="listaArchivos" class="space-y-3"></div>
                            </div>
                            
                            <!-- Botón de envío -->
                            <div class="flex justify-end">
                                <button type="submit" id="btnSubir" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-upload mr-2"></i>Subir Fotos
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?>

<script>
// Funciones para el modal de fotos
function abrirModalFotos() {
    document.getElementById('modalFotos').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevenir scroll del body
}

function cerrarModalFotos() {
    document.getElementById('modalFotos').classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restaurar scroll del body
}

// Cerrar modal al hacer clic fuera de él
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalFotos');
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            cerrarModalFotos();
        }
    });
    
    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            cerrarModalFotos();
        }
    });
});

// Función para mostrar archivos seleccionados
function mostrarArchivosSeleccionados() {
    const input = document.getElementById('fotos');
    const container = document.getElementById('archivosSeleccionados');
    const lista = document.getElementById('listaArchivos');
    const btnSubir = document.getElementById('btnSubir');
    
    if (input.files.length > 0) {
        container.classList.remove('hidden');
        lista.innerHTML = '';
        btnSubir.disabled = false;
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const fileDiv = document.createElement('div');
            fileDiv.className = 'flex items-center space-x-3 p-3 bg-gray-50 rounded-lg';
            
            // Crear vista previa si es imagen
            let preview = '';
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview = `<img src="${e.target.result}" class="w-16 h-16 object-cover rounded" alt="Preview">`;
                    fileDiv.querySelector('.preview-container').innerHTML = preview;
                };
                reader.readAsDataURL(file);
            }
            
            fileDiv.innerHTML = `
                <div class="preview-container w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                    <i class="fas fa-image text-gray-400"></i>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-medium text-gray-700">${file.name}</div>
                    <div class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                    <input type="text" name="descripciones[]" 
                        placeholder="Descripción de la foto (opcional)" 
                        class="w-full mt-2 border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="button" onclick="removerArchivo(${i})" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            lista.appendChild(fileDiv);
        }
    } else {
        container.classList.add('hidden');
        btnSubir.disabled = true;
    }
}

// Función para remover archivo de la selección
function removerArchivo(index) {
    const input = document.getElementById('fotos');
    const dt = new DataTransfer();
    
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) {
            dt.items.add(input.files[i]);
        }
    }
    
    input.files = dt.files;
    mostrarArchivosSeleccionados();
}

// Drag and drop para archivos
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('.border-dashed');
    const input = document.getElementById('fotos');
    
    if (dropZone && input) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            // Combinar archivos existentes con nuevos
            const existingFiles = Array.from(input.files);
            const newFiles = Array.from(files);
            const allFiles = [...existingFiles, ...newFiles];
            
            // Crear nuevo DataTransfer
            const newDt = new DataTransfer();
            allFiles.forEach(file => newDt.items.add(file));
            input.files = newDt.files;
            
            mostrarArchivosSeleccionados();
        }
    }
});

// Función para cambiar foto de portada
function cambiarPortada(idFoto) {
    console.log('Intentando cambiar portada para foto ID:', idFoto);
    if (confirm('¿Estás seguro de que quieres hacer esta foto la portada?')) {
        // Crear un formulario temporal y enviarlo
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/hogar%20ideal/propiedad/setPortada/' + idFoto;
        
        console.log('Formulario creado con acción:', form.action);
        
        // Agregar el formulario al DOM y enviarlo
        document.body.appendChild(form);
        form.submit();
    }
}

// Función para quitar portada
function quitarPortada(idFoto) {
    console.log('Intentando quitar portada para foto ID:', idFoto);
    if (confirm('¿Estás seguro de que quieres quitar la portada de esta foto?')) {
        // Crear un formulario temporal y enviarlo
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/hogar%20ideal/propiedad/quitarPortada/' + idFoto;
        
        console.log('Formulario creado con acción:', form.action);
        
        // Agregar el formulario al DOM y enviarlo
        document.body.appendChild(form);
        form.submit();
    }
}

// Función para eliminar foto
function eliminarFoto(idFoto) {
    console.log('Intentando eliminar foto ID:', idFoto);
    if (confirm('¿Estás seguro de que quieres eliminar esta foto?')) {
        // Crear un formulario temporal y enviarlo
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/hogar%20ideal/propiedad/deleteFoto/' + idFoto;
        
        console.log('Formulario creado con acción:', form.action);
        
        // Agregar el formulario al DOM y enviarlo
        document.body.appendChild(form);
        form.submit();
    }
}


</script> 