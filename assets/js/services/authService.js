async function iniciarSesion(email, password) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    const response = await fetch('/bloc-c/api/auth/login.php', {
        method: 'POST',
        body: formData
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al iniciar sesión');
    }

    return data;
}

async function registrarUsuario(nombre, apellido, email, password, confirmPassword) {
    const formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('apellido', apellido);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('confirmPassword', confirmPassword);

    const response = await fetch('/bloc-c/api/auth/register.php', {
        method: 'POST',
        body: formData
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al registrarse');
    }

    return data;
}

async function cerrarSesion() {
    const response = await fetch('/bloc-c/api/auth/logout.php', {
        method: 'POST'
    });

    if (!response.ok) {
        throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.status !== 'success') {
        throw new Error(data.message || 'Error al cerrar sesión');
    }

    return data;
}

export { iniciarSesion, registrarUsuario, cerrarSesion };
