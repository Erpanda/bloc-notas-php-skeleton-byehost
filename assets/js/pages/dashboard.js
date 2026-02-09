import { mostrarMensaje } from '../utils/helpers.js';
import { obtenerEstadisticas, obtenerTareas, eliminarTarea, toggleTarea } from '../services/taskService.js';

const deleteState = {
    id: null
};

async function cargarDashboard() {
    // mostrar ambos skeletons al inicio
    toggleCarga('stats-skeleton', 'stats-real', true);
    toggleCarga('tasks-skeleton', 'listaTareas', true);

    try {
        await Promise.all([
            cargarEstadisticasInterno(),
            cargarTareasInterno()
        ]);
    } catch (error) {
        mostrarMensaje('Error al cargar el dashboard', 'danger');
    } finally {
        // ocultar ambos AL MISMO TIEMPO
        toggleCarga('stats-skeleton', 'stats-real', false);
        toggleCarga('tasks-skeleton', 'listaTareas', false);
    }
}

// Función para alternar entre el esqueleto y el contenido real
function toggleCarga(skeletonId, realId, cargando) {
    const sk = document.getElementById(skeletonId);
    const real = document.getElementById(realId);

    if (!sk || !real) return;

    sk.classList.toggle('d-none', !cargando);
    real.classList.toggle('d-none', cargando);
}

// Cargar estadísticas
async function cargarEstadisticasInterno() {
    const data = await obtenerEstadisticas();

    if (data) {
        document.getElementById('totalTareas').textContent = data.total;
        document.getElementById('tareasCompletadas').textContent = data.completadas;
        document.getElementById('tareasPendientes').textContent = data.pendientes;
    }
}

// Cargar lista de tareas
async function cargarTareasInterno() {
    const data = await obtenerTareas();

    if (data) {
        mostrarTareas(data);
    }
}

// Renderizar tareas en el DOM
function mostrarTareas(tareas) {
    const contenedor = document.getElementById('listaTareas');
    
    if (!contenedor) return;

    if (tareas.length === 0) {
        contenedor.classList.add('justify-content-center');
        contenedor.innerHTML = `
            <div class="d-flex justify-content-center align-items-center text-center text-muted py-5">
                <p class="mb-0">No tienes tareas todavía. ¡Crea una nueva!</p>
            </div>
        `;
        return;
    }
    
    contenedor.innerHTML = tareas.map(tarea => `
        <div class="col">
            <div class="card mb-3 border-start ${tarea.completed ? 'border-success border-4 bg-light' : 'border-warning border-4'}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start overflow-hidden">
                        <div class="flex-grow-1">
                            <h5 class="card-title ${tarea.completed ? 'text-decoration-line-through text-muted' : ''} fw-bold">
                                ${tarea.title}
                            </h5>
                            <p class="card-text text-muted">${tarea.description || 'Sin descripción'}</p>
                            <p class="card-text text-muted">Fecha límite: <span class="text-black fw-bold">${tarea.due_date ? new Date(tarea.due_date).toISOString().split('T')[0] : 'Sin fecha límite'}</span></p>
                            <small class="${tarea.completed ? 'text-success' : 'text-muted'} fs-6">Estado: 
                                ${tarea.completed ? 'Completada' : 'Pendiente'}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <button 
                            class="btn btn-sm ${tarea.completed ? 'btn-warning' : 'btn-primary'}"
                            data-action="toggle"
                            data-id="${tarea.id}"
                            data-completed="${tarea.completed}">
                            ${tarea.completed ? 'Reiniciar' : 'Completar'}
                        </button>
                        <button 
                            class="btn btn-sm btn-danger"
                            data-action="delete"
                            data-id="${tarea.id}">
                            Eliminar
                        </button>
                        <button 
                            class="btn btn-sm btn-warning"
                            data-action="edit"
                            data-id="${tarea.id}"
                            ${tarea.completed ? 'disabled' : ''}>
                            Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;

    const { action, id, completed } = btn.dataset;

    if (action === 'delete') {
        deleteState.id = id;
        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('confirmDeleteModal')
        ).show();
        return;
    }

    if (action === 'toggle') {
        try {
            const nuevoEstado = completed !== 'true';

            const resul = await toggleTarea(id, nuevoEstado);

            mostrarMensaje(resul.message, 'success');
            await cargarTareasInterno();
            await cargarEstadisticasInterno();
        } catch (err) {
            mostrarMensaje(err.message, 'danger');
        }
    }

    if (action === 'edit') {
        editarTareaClick(id);
    }
});

document.getElementById('btnConfirmDelete')
    .addEventListener('click', async () => {

        if (!deleteState.id) return;

        try {
            const resul = await eliminarTarea(deleteState.id);

            mostrarMensaje(resul.message, 'success');
            await cargarTareasInterno();
            await cargarEstadisticasInterno();

        } catch (err) {
            mostrarMensaje(err.message, 'danger');
        } finally {
            deleteState.id = null;
            bootstrap.Modal
                .getInstance(document.getElementById('confirmDeleteModal'))
                .hide();
        }
    });

async function editarTareaClick(taskId) {
    window.location.href = `createNote?id=${taskId}`;
}

// Cargar al iniciar
document.addEventListener('DOMContentLoaded', () => {
    cargarDashboard();
});