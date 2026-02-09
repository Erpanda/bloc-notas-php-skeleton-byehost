// Mostrar mensajes de error o éxito
function mostrarMensaje(mensaje, tipo) {
    const el = document.getElementById('appMensaje');
    if (!el) return;

    const tipoAlerta = tipo === 'success' ? 'alert-success' : 'alert-danger';
    el.textContent = mensaje;
    el.classList.add(tipoAlerta);
    el.classList.remove('d-none');

    setTimeout(() => {
        el.classList.add('d-none');
        el.textContent = '';
        el.classList.remove(tipoAlerta);
    }, 1200);
}

export { mostrarMensaje };