import { mostrarMensaje } from '../utils/helpers.js';
import { registrarUsuario } from '../services/authService.js';

document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const terms = document.getElementById('terms').checked;

    if (!nombre || !apellido || !email || !password || !confirmPassword || !terms) {
        mostrarMensaje('Por favor, complete todos los campos', 'warning');
        return;
    }

    if (password !== confirmPassword) {
        mostrarMensaje('Las contraseñas no coinciden', 'warning');
        return;
    }

    try {
        const resultado = await registrarUsuario(nombre, apellido, email, password, confirmPassword, terms);
        
        mostrarMensaje(resultado.message || 'Registrando usuario...', 'success');
        
        setTimeout(() => {
            window.location.href = resultado.redirect || 'login';
        }, 1000);
    } catch (error) {
        mostrarMensaje(error.message, 'danger');
    }
});