import { cerrarSesion } from '../services/authService.js';
import { mostrarMensaje } from './helpers.js';

document.getElementById('btnLogout').addEventListener('click', async e => {
    e.preventDefault();    
    
    try {
        const resultado = await cerrarSesion();
        
        window.location.href = resultado.redirect || 'home';
    } catch (error) {
        mostrarMensaje(error.message, 'danger');
    }
});