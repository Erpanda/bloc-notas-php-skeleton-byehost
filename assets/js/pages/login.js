import { mostrarMensaje } from '../utils/helpers.js';
import { iniciarSesion } from '../services/authService.js';

document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (!email || !password) {
        mostrarMensaje('Por favor, complete todos los campos', 'warning');
        return;
    }

    try {
        const resultado = await iniciarSesion(email, password);
        
        mostrarMensaje(resultado.message || 'Iniciando sesión...', 'success');
        
        setTimeout(() => {
            window.location.href = resultado.redirect || 'dashboard';
        }, 1000);
    } catch (error) {
        mostrarMensaje(error.message, 'danger');
    }
});