@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Listado de Pacientes</h5>
                <button class="btn btn-light btn-sm" onclick="mostrarFormularioNuevo()">
                    <i class="fas fa-plus"></i> Nuevo Paciente
                </button>
            </div>
            <div class="card-body">
                <!-- Barra de búsqueda -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-input" 
                                   placeholder="Buscar por nombre, documento o correo...">
                            <button class="btn btn-outline-secondary" type="button" onclick="buscarPacientes()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de pacientes -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Correo</th>
                                <th>Género</th>
                                <th>Municipio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-pacientes">
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div id="pagination-info"></div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Formulario -->
<div class="modal fade" id="pacienteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modal-title">Nuevo Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paciente-form">
                    <input type="hidden" id="paciente-id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo Documento</label>
                            <select class="form-control" id="tipo_documento_id" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número Documento</label>
                            <input type="text" class="form-control" id="numero_documento" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Primer Nombre</label>
                            <input type="text" class="form-control" id="nombre1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" class="form-control" id="nombre2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Primer Apellido</label>
                            <input type="text" class="form-control" id="apellido1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" class="form-control" id="apellido2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Género</label>
                            <select class="form-control" id="genero_id" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Departamento</label>
                            <select class="form-control" id="departamento_id" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Municipio</label>
                            <select class="form-control" id="municipio_id" required>
                                <option value="">Primero seleccione departamento</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" required>
                    </div>

                    <div id="form-errors" class="alert alert-danger" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPaciente()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea eliminar este paciente?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    function validarFormulario() {
        const errors = [];
        
        const tipo_documento_id = document.getElementById('tipo_documento_id').value;
        const numero_documento = document.getElementById('numero_documento').value.trim();
        const nombre1 = document.getElementById('nombre1').value.trim();
        const apellido1 = document.getElementById('apellido1').value.trim();
        const genero_id = document.getElementById('genero_id').value;
        const departamento_id = document.getElementById('departamento_id').value;
        const municipio_id = document.getElementById('municipio_id').value;
        const correo = document.getElementById('correo').value.trim();

        // Validar campos obligatorios
        if (!tipo_documento_id) {
            errors.push('Debe seleccionar un tipo de documento');
        }
        
        if (!numero_documento) {
            errors.push('El número de documento es obligatorio');
        } else if (numero_documento.length < 5) {
            errors.push('El número de documento debe tener al menos 5 caracteres');
        } else if (numero_documento.length > 20) {
            errors.push('El número de documento no puede tener más de 20 caracteres');
        } else if (!/^\d+$/.test(numero_documento)) {
            errors.push('El número de documento solo debe contener números');
        }
        
        if (!nombre1) {
            errors.push('El primer nombre es obligatorio');
        } else if (nombre1.length < 2) {
            errors.push('El primer nombre debe tener al menos 2 caracteres');
        } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre1)) {
            errors.push('El primer nombre solo debe contener letras');
        }
        
        const nombre2 = document.getElementById('nombre2').value.trim();
        if (nombre2 && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre2)) {
            errors.push('El segundo nombre solo debe contener letras');
        }
        
        if (!apellido1) {
            errors.push('El primer apellido es obligatorio');
        } else if (apellido1.length < 2) {
            errors.push('El primer apellido debe tener al menos 2 caracteres');
        } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido1)) {
            errors.push('El primer apellido solo debe contener letras');
        }
        
        const apellido2 = document.getElementById('apellido2').value.trim();
        if (apellido2 && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido2)) {
            errors.push('El segundo apellido solo debe contener letras');
        }
        
        if (!genero_id) {
            errors.push('Debe seleccionar un género');
        }
        
        if (!departamento_id) {
            errors.push('Debe seleccionar un departamento');
        }
        
        if (!municipio_id) {
            errors.push('Debe seleccionar un municipio');
        }
        
        if (!correo) {
            errors.push('El correo electrónico es obligatorio');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
            errors.push('Ingrese un correo electrónico válido');
        } else if (correo.length > 100) {
            errors.push('El correo no puede tener más de 100 caracteres');
        }
        
        return errors;
    }

    // Validación en tiempo real
    function setupRealTimeValidation() {
        const inputs = [
            'numero_documento', 'nombre1', 'nombre2', 
            'apellido1', 'apellido2', 'correo'
        ];
        
        inputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function() {
                    // Quitar clase de error si existe
                    this.classList.remove('is-invalid');
                    
                    // Validaciones específicas
                    if (id === 'correo') {
                        if (this.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
                            this.classList.add('is-invalid');
                        }
                    } else if (id === 'numero_documento') {
                        if (this.value && !/^\d+$/.test(this.value)) {
                            this.classList.add('is-invalid');
                        }
                    } else if (id.includes('nombre') || id.includes('apellido')) {
                        if (this.value && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(this.value)) {
                            this.classList.add('is-invalid');
                        }
                    }
                });
            }
        });
    }

    // Llamar a esta función al cargar el modal
    document.getElementById('pacienteModal').addEventListener('shown.bs.modal', function() {
        setupRealTimeValidation();
    });

    let currentPage = 1;
    let currentSearch = '';
    let deleteId = null;

    document.addEventListener('DOMContentLoaded', function() {
        cargarPacientes();
        cargarFormData();
    });

    // Cargar pacientes
    async function cargarPacientes(page = 1) {
        const token = localStorage.getItem('token');
        
        if (!token) {
            window.location.href = '/login';
            return;
        }

        try {
            const url = `/api/pacientes?page=${page}&search=${currentSearch}`;
            
            const response = await fetch(url, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (response.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/login';
                return;
            }

            const data = await response.json();
            
            if (data.success) {
                mostrarTabla(data.data);
                mostrarPaginacion(data.pagination);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Mostrar tabla
    function mostrarTabla(pacientes) {
        const tbody = document.getElementById('tabla-pacientes');
        
        if (!pacientes || pacientes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay pacientes</td></tr>';
            return;
        }

        let html = '';
        pacientes.forEach(p => {
            
            html += `<tr>
                <td>${p.tipo_documento?.nombre || ''} ${p.numero_documento || ''}</td>
                <td>${p.nombre1 || ''} ${p.nombre2 || ''}</td>
                <td>${p.apellido1 || ''} ${p.apellido2 || ''}</td>
                <td>${p.correo || ''}</td>
                <td>${p.genero?.nombre || ''}</td>
                <td>${p.municipio?.nombre || ''}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editarPaciente(${p.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(${p.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        });
        
        tbody.innerHTML = html;
    }

    // Cargar datos para formulario
    async function cargarFormData() {
        const token = localStorage.getItem('token');
        
        try {
            const response = await fetch('/api/pacientes-form-data', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                llenarSelect('tipo_documento_id', data.data.tipos_documento);
                llenarSelect('genero_id', data.data.generos);
                llenarSelect('departamento_id', data.data.departamentos);
                
                document.getElementById('departamento_id').addEventListener('change', function() {
                    const deptoId = this.value;
                    const depto = data.data.departamentos.find(d => d.id == deptoId);
                    llenarSelect('municipio_id', depto ? depto.municipios : []);
                });
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function llenarSelect(id, items) {
        const select = document.getElementById(id);
        select.innerHTML = '<option value="">Seleccione...</option>';
        items.forEach(item => {
            select.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
        });
    }

    // Nuevo paciente
    function mostrarFormularioNuevo() {
        document.getElementById('modal-title').textContent = 'Nuevo Paciente';
        document.getElementById('paciente-form').reset();
        document.getElementById('paciente-id').value = '';
        document.getElementById('form-errors').style.display = 'none';
        
        const modal = new bootstrap.Modal(document.getElementById('pacienteModal'));
        modal.show();
    }

    // Editar paciente
    async function editarPaciente(id) {
        console.log('Editando:', id);
        const token = localStorage.getItem('token');
        
        try {
            const response = await fetch(`/api/pacientes/${id}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const p = data.data;
                document.getElementById('modal-title').textContent = 'Editar Paciente';
                document.getElementById('paciente-id').value = p.id;
                document.getElementById('tipo_documento_id').value = p.tipo_documento.id;
                document.getElementById('numero_documento').value = p.numero_documento;
                document.getElementById('nombre1').value = p.nombre1;
                document.getElementById('nombre2').value = p.nombre2 || '';
                document.getElementById('apellido1').value = p.apellido1;
                document.getElementById('apellido2').value = p.apellido2 || '';
                document.getElementById('genero_id').value = p.genero.id;
                document.getElementById('departamento_id').value = p.departamento.id;
                
                // Cargar municipios
                setTimeout(() => {
                    const event = new Event('change');
                    document.getElementById('departamento_id').dispatchEvent(event);
                    
                    setTimeout(() => {
                        document.getElementById('municipio_id').value = p.municipio.id;
                    }, 100);
                }, 100);
                
                document.getElementById('correo').value = p.correo;
                
                const modal = new bootstrap.Modal(document.getElementById('pacienteModal'));
                modal.show();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar datos');
        }
    }

    // Guardar paciente
    async function guardarPaciente() {

        const errors = validarFormulario();
        
        if (errors.length > 0) {
            const errorDiv = document.getElementById('form-errors');
            errorDiv.innerHTML = errors.map(e => `• ${e}`).join('<br>');
            errorDiv.style.display = 'block';
            
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        
        // Ocultar errores si todo está bien
        document.getElementById('form-errors').style.display = 'none';
        
        const token = localStorage.getItem('token');
        const id = document.getElementById('paciente-id').value;
        const url = id ? `/api/pacientes/${id}` : '/api/pacientes';
        const method = id ? 'PUT' : 'POST';
        
        // Mostrar indicador de carga
        const btnGuardar = event.target;
        const textoOriginal = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
        btnGuardar.disabled = true;
        
        const data = {
            tipo_documento_id: document.getElementById('tipo_documento_id').value,
            numero_documento: document.getElementById('numero_documento').value.trim(),
            nombre1: document.getElementById('nombre1').value.trim(),
            nombre2: document.getElementById('nombre2').value.trim() || null,
            apellido1: document.getElementById('apellido1').value.trim(),
            apellido2: document.getElementById('apellido2').value.trim() || null,
            genero_id: document.getElementById('genero_id').value,
            departamento_id: document.getElementById('departamento_id').value,
            municipio_id: document.getElementById('municipio_id').value,
            correo: document.getElementById('correo').value.trim()
        };

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('pacienteModal')).hide();
                
                cargarPacientes();
                
                mostrarNotificacion(result.message, 'success');
            } else {
                // Mostrar errores del servidor
                const errorDiv = document.getElementById('form-errors');
                if (result.errors) {
                    errorDiv.innerHTML = Object.values(result.errors).flat().map(e => `• ${e}`).join('<br>');
                } else {
                    errorDiv.innerHTML = `• ${result.message || 'Error al guardar'}`;
                }
                errorDiv.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarNotificacion('Error de conexión con el servidor', 'danger');
        } finally {
            btnGuardar.innerHTML = textoOriginal;
            btnGuardar.disabled = false;
        }
    }

    // Eliminar
    function confirmarEliminar(id) {
        deleteId = id;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    document.getElementById('confirm-delete')?.addEventListener('click', async function() {
        if (!deleteId) return;
        
        const token = localStorage.getItem('token');
        
        try {
            const response = await fetch(`/api/pacientes/${deleteId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                cargarPacientes();
                alert(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al eliminar');
        }
        
        deleteId = null;
    });

    // Buscar
    function buscarPacientes() {
        currentSearch = document.getElementById('search-input').value;
        cargarPacientes(1);
    }

    // Paginación
    function mostrarPaginacion(pagination) {
        if (!pagination) return;
        
        const info = document.getElementById('pagination-info');
        info.textContent = `Página ${pagination.current_page} de ${pagination.last_page} (Total: ${pagination.total})`;
        
        let html = '';
        for (let i = 1; i <= pagination.last_page; i++) {
            html += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cargarPacientes(${i}); return false;">${i}</a>
            </li>`;
        }
        
        document.getElementById('pagination').innerHTML = html;
    }

    function mostrarNotificacion(mensaje, tipo = 'success') {
        // Crear elemento de notificación
        const notificacion = document.createElement('div');
        notificacion.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        notificacion.style.zIndex = '9999';
        notificacion.style.minWidth = '300px';
        notificacion.innerHTML = `
            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.remove();
        }, 3000);
    }
</script>
@endsection