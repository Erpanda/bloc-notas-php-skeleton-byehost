async function obtenerEstadisticas() {
    const response = await fetch('/bloc-c/api/tasks/stats.php');
    const data = await response.json();
    
    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al cargar estadísticas');
    }
    
    return data.data;
};

async function obtenerTareas() {
    const response = await fetch('/bloc-c/api/tasks/list.php');
    const data = await response.json();
    
    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al cargar tareas');
    }
    
    return data.data;
}

async function eliminarTarea(taskId) {
    const response = await fetch('/bloc-c/api/tasks/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: taskId })
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al eliminar');
    }

    return data;
}

async function toggleTarea(taskId, completed) {
    const response = await fetch('/bloc-c/api/tasks/completeTask.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ taskId: taskId, completed: completed })
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message);
    }

    return data;
}

async function obtenerTareaPorId(taskId) {
    const response = await fetch('/bloc-c/api/tasks/dataNote.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: taskId })
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al obtener tarea');
    }

    return data.data;
}

async function crearTarea(titulo, descripcion, fechaLimite) {
    const response = await fetch('/bloc-c/api/tasks/create.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            title: titulo,
            description: descripcion,
            due_date: fechaLimite
        })
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al crear tarea');
    }

    return data;
}

async function actualizarTarea(taskId, titulo, descripcion, fechaLimite) {
    const response = await fetch('/bloc-c/api/tasks/update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            taskId: taskId,
            title: titulo,
            description: descripcion,
            due_date: fechaLimite
        })
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al actualizar tarea');
    }

    return data;
}

export {
    obtenerEstadisticas,
    obtenerTareas,
    eliminarTarea,
    toggleTarea,
    obtenerTareaPorId,
    crearTarea,
    actualizarTarea
};