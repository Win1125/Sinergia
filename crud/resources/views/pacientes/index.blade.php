@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h5>Listado de Pacientes</h5>
                <button class="btn btn-light btn-sm" onclick="mostrarFormulario()">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>
            <div class="card-body">
                <!-- Búsqueda -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" placeholder="Buscar...">
                            <button class="btn btn-outline-secondary" onclick="buscar()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                <td colspan="7" class="text-center">Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div id="paginacion" class="d-flex justify-content-center"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Formulario -->
<div class="modal fade" id="pacienteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Nuevo Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="pacienteForm">
                    <input type="hidden" id="paciente_id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tipo Documento</label>
                            <select class="form-control" id="tipo_documento_id" required></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Número Documento</label>
                            <input type="text" class="form-control" id="numero_documento" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Primer Nombre</label>
                            <input type="text" class="form-control" id="nombre1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Segundo Nombre</label>
                            <input type="text" class="form-control" id="nombre2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Primer Apellido</label>
                            <input type="text" class="form-control" id="apellido1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Segundo Apellido</label>
                            <input type="text" class="form-control" id="apellido2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Género</label>
                            <select class="form-control" id="genero_id" required></select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Departamento</label>
                            <select class="form-control" id="departamento_id" required></select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Municipio</label>
                            <select class="form-control" id="municipio_id" required></select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Correo</label>
                        <input type="email" class="form-control" id="correo" required>
                    </div>

                    <div id="formError" class="alert alert-danger" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let token = localStorage.getItem('token');
    let currentPage = 1;
    let currentSearch = '';

    // Verificar autenticación
    if (!token) {
        window.location.href = '/login';
    }

    // Cargar datos al inicio
    document.addEventListener('DOMContentLoaded', function() {
        cargarPacientes();
        cargarFormData();
    });

    // Cargar pacientes
    async function cargarPacientes(page = 1) {
        const url = `/api/pacientes?page=${page}&search=${currentSearch}`;
        const response = await fetch(url, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        
        if (data.success) {
            mostrarTabla(data.data);
            mostrarPaginacion(data.pagination);
        }
    }

    // Mostrar tabla
    function mostrarTabla(pacientes) {
        let html = '';
        pacientes.forEach(p => {
            html += `<tr>
                <td>${p.tipo_documento.nombre} ${p.numero_documento}</td>
                <td>${p.nombres.nombre1} ${p.nombres.nombre2 || ''}</td>
                <td>${p.apellidos.apellido1} ${p.apellidos.apellido2 || ''}</td>
                <td>${p.correo}</td>
                <td>${p.genero.nombre}</td>
                <td>${p.municipio.nombre}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editar(${p.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="eliminar(${p.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        });
        document.getElementById('tabla-pacientes').innerHTML = html;
    }

    // Cargar datos para selects
    async function cargarFormData() {
        const response = await fetch('/api/pacientes-form-data', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        
        if (data.success) {
            // Llenar selects
            llenarSelect('tipo_documento_id', data.data.tipos_documento);
            llenarSelect('genero_id', data.data.generos);
            llenarSelect('departamento_id', data.data.departamentos);
            
            // Evento cambio de departamento
            document.getElementById('departamento_id').addEventListener('change', function() {
                const depto = data.data.departamentos.find(d => d.id == this.value);
                llenarSelect('municipio_id', depto ? depto.municipios : []);
            });
        }
    }

    // Función helper para llenar selects
    function llenarSelect(id, items) {
        const select = document.getElementById(id);
        select.innerHTML = '<option value="">Seleccione...</option>';
        items.forEach(item => {
            select.innerHTML += `<option value="${item.id}">${item.nombre}</option>`;
        });
    }

    // Guardar paciente
    async function guardar() {
        const id = document.getElementById('paciente_id').value;
        const url = id ? `/api/pacientes/${id}` : '/api/pacientes';
        const method = id ? 'PUT' : 'POST';
        
        const data = {
            tipo_documento_id: document.getElementById('tipo_documento_id').value,
            numero_documento: document.getElementById('numero_documento').value,
            nombre1: document.getElementById('nombre1').value,
            nombre2: document.getElementById('nombre2').value || null,
            apellido1: document.getElementById('apellido1').value,
            apellido2: document.getElementById('apellido2').value || null,
            genero_id: document.getElementById('genero_id').value,
            departamento_id: document.getElementById('departamento_id').value,
            municipio_id: document.getElementById('municipio_id').value,
            correo: document.getElementById('correo').value
        };

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
            alert(result.message);
        } else {
            document.getElementById('formError').innerHTML = Object.values(result.errors || {}).join('<br>');
            document.getElementById('formError').style.display = 'block';
        }
    }

    // Otras funciones (editar, eliminar, buscar, paginación)
    function mostrarFormulario() {
        document.getElementById('pacienteForm').reset();
        document.getElementById('paciente_id').value = '';
        document.getElementById('modalTitle').innerText = 'Nuevo Paciente';
        new bootstrap.Modal(document.getElementById('pacienteModal')).show();
    }

    async function editar(id) {
        const response = await fetch(`/api/pacientes/${id}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        const data = await response.json();
        
        if (data.success) {
            const p = data.data;
            document.getElementById('paciente_id').value = p.id;
            document.getElementById('tipo_documento_id').value = p.tipo_documento.id;
            document.getElementById('numero_documento').value = p.numero_documento;
            document.getElementById('nombre1').value = p.nombres.nombre1;
            document.getElementById('nombre2').value = p.nombres.nombre2 || '';
            document.getElementById('apellido1').value = p.apellidos.apellido1;
            document.getElementById('apellido2').value = p.apellidos.apellido2 || '';
            document.getElementById('genero_id').value = p.genero.id;
            document.getElementById('departamento_id').value = p.departamento.id;
            setTimeout(() => {
                document.getElementById('municipio_id').value = p.municipio.id;
            }, 500);
            document.getElementById('correo').value = p.correo;
            
            document.getElementById('modalTitle').innerText = 'Editar Paciente';
            new bootstrap.Modal(document.getElementById('pacienteModal')).show();
        }
    }

    async function eliminar(id) {
        if (confirm('¿Eliminar paciente?')) {
            const response = await fetch(`/api/pacientes/${id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await response.json();
            if (data.success) {
                cargarPacientes();
                alert(data.message);
            }
        }
    }

    function buscar() {
        currentSearch = document.getElementById('search').value;
        cargarPacientes(1);
    }

    function mostrarPaginacion(pagination) {
        let html = '<ul class="pagination">';
        for (let i = 1; i <= pagination.last_page; i++) {
            html += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="cargarPacientes(${i})">${i}</a>
            </li>`;
        }
        html += '</ul>';
        document.getElementById('paginacion').innerHTML = html;
    }

    function logout() {
        localStorage.removeItem('token');
        window.location.href = '/';
    }
</script>
@endsection