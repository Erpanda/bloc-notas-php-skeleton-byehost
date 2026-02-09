import { mostrarMensaje } from '../utils/helpers.js';
import { obtenerTareaPorId, crearTarea, actualizarTarea } from '../services/taskService.js';
import { validarTituloTarea, validarDescripcionTarea, validarFechaLimiteTarea } from '../utils/validators.js';

const taskId = new URLSearchParams(window.location.search).get('id');
const esEdicion = !!taskId;

function toggleFormCarga(cargando) {
    const skeleton = document.getElementById('form-skeleton');
    const form = document.getElementById('formNuevaTarea');

    if (!skeleton || !form) return;

    if (!esEdicion) {
        skeleton.classList.add('d-none');
        form.classList.remove('d-none');
        return;
    }

    skeleton.classList.toggle('d-none', !cargando);
    form.classList.toggle('d-none', cargando);
}

async function manejarAccionTarea(e) {    
    e.preventDefault();
    
    const titulo = document.getElementById('tituloTarea').value.trim();
    const descripcion = document.getElementById('descripcionTarea').value.trim();
    const fechaLimite = document.getElementById('fechaLimite').value;
    
    if (!validarTituloTarea(titulo)) {
        mostrarMensaje('El título debe tener al menos 1 caracter', 'danger');
        return;
    }
    
    if (!validarDescripcionTarea(descripcion)) {
        mostrarMensaje('La descripción debe tener al menos 1 caracter', 'danger');
        return;
    }
    
    if (!validarFechaLimiteTarea(fechaLimite)) {
        mostrarMensaje('La fecha límite debe ser mayor o igual a la fecha actual', 'danger');
        return;
    }

    try {
        // Crear o actualizar tarea
        const resultado = await (
            esEdicion
                ? actualizarTarea(taskId, titulo, descripcion, fechaLimite)
                : crearTarea(titulo, descripcion, fechaLimite)
        );

        mostrarMensaje(resultado.message, 'success');
        
        setTimeout(() => {
            window.location.href = 'dashboard';
        }, 1200);
    } catch (error) {
        mostrarMensaje(error.message, 'danger');
        console.error('Error:', error);
    }
}

async function cargarDatosTarea() {
    toggleFormCarga(true);

    try {
        const tarea = await obtenerTareaPorId(taskId);

        if (!tarea) {
            mostrarMensaje('Tarea no encontrada', 'danger');
            setTimeout(() => window.location.href = 'dashboard', 2000);
            return;
        }

        document.getElementById('tituloTarea').value = tarea.title || '';
        document.getElementById('descripcionTarea').value = tarea.description || '';
        document.getElementById('fechaLimite').value =
            tarea.due_date ? tarea.due_date.split('T')[0] : '';
        document.getElementById('btnAction').innerHTML = 
            '<i class="bi bi-check2 me-2"></i>Actualizar Tarea';

    } catch (error) {
        mostrarMensaje(error.message || 'Error al cargar la tarea', 'danger');
        setTimeout(() => window.location.href = 'dashboard', 2000);
    } finally {
        toggleFormCarga(false);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const formNuevaTarea = document.getElementById('formNuevaTarea');
    const btnCancelar = document.getElementById('btnCancelar');

    if (!formNuevaTarea) return;

    toggleFormCarga(false);

    if (esEdicion) {
        cargarDatosTarea();
    }

    formNuevaTarea.addEventListener('submit', manejarAccionTarea);
    btnCancelar?.addEventListener('click', () => {
        window.location.href = 'dashboard';
    });
});