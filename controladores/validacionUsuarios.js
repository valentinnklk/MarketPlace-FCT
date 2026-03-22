// Validación de formularios (panel admin, registro, login)
document.addEventListener('DOMContentLoaded', function() {
    
    function estaVacio(valor) {
        if (!valor) return true;
        if (valor.trim() === '') return true;
        return false;
    }
    
    function mostrarError(campoId, errorId, mensaje) {
        let campo = document.getElementById(campoId);
        let errorDiv = document.getElementById(errorId);
        if (campo) campo.classList.add('campo-error');
        if (errorDiv) errorDiv.textContent = mensaje;
    }
    
    function limpiarError(campoId, errorId) {
        let campo = document.getElementById(campoId);
        let errorDiv = document.getElementById(errorId);
        if (campo) campo.classList.remove('campo-error');
        if (errorDiv) errorDiv.textContent = '';
    }
    
    // Validar formulario de panel administración (normal y admin)
    let formularioNormal = document.getElementById('formNormal');
    let formularioAdmin = document.getElementById('formAdmin');
    
    function validarFormularioCompleto(formulario, tipo) {
        if (!formulario) return;
        
        formulario.addEventListener('submit', function(evento) {
            let hayError = false;
            
            let nombre = document.getElementById('nombre' + tipo).value;
            let email = document.getElementById('email' + tipo).value;
            let password = document.getElementById('password' + tipo).value;
            let ubicacion = document.getElementById('ubicacion' + tipo).value;
            
            if (estaVacio(nombre)) {
                mostrarError('nombre' + tipo, 'errorNombre' + tipo, 'El nombre es obligatorio');
                hayError = true;
            } else if (nombre.trim().length < 4) {
                mostrarError('nombre' + tipo, 'errorNombre' + tipo, 'El nombre debe tener al menos 4 caracteres');
                hayError = true;
            } else {
                limpiarError('nombre' + tipo, 'errorNombre' + tipo);
            }
            
            if (estaVacio(email)) {
                mostrarError('email' + tipo, 'errorEmail' + tipo, 'El email es obligatorio');
                hayError = true;
            } else {
                limpiarError('email' + tipo, 'errorEmail' + tipo);
            }
            
            if (estaVacio(password)) {
                mostrarError('password' + tipo, 'errorPassword' + tipo, 'La contraseña es obligatoria');
                hayError = true;
            } else if (password.length < 6) {
                mostrarError('password' + tipo, 'errorPassword' + tipo, 'La contraseña debe tener al menos 6 caracteres');
                hayError = true;
            } else {
                limpiarError('password' + tipo, 'errorPassword' + tipo);
            }
            
            if (estaVacio(ubicacion)) {
                mostrarError('ubicacion' + tipo, 'errorUbicacion' + tipo, 'La ubicación es obligatoria');
                hayError = true;
            } else if (ubicacion.trim().length < 3) {
                mostrarError('ubicacion' + tipo, 'errorUbicacion' + tipo, 'La ubicación debe tener al menos 3 caracteres');
                hayError = true;
            } else {
                limpiarError('ubicacion' + tipo, 'errorUbicacion' + tipo);
            }
            
            if (hayError) {
                evento.preventDefault();
            }
        });
    }
    
    validarFormularioCompleto(formularioNormal, 'Normal');
    validarFormularioCompleto(formularioAdmin, 'Admin');
    
    // Validar formulario de registro
    let formularioRegistro = document.getElementById('formRegistro');
    
    if (formularioRegistro) {
        formularioRegistro.addEventListener('submit', function(evento) {
            let hayError = false;
            
            let nombre = document.getElementById('nombreRegistro').value;
            let email = document.getElementById('emailRegistro').value;
            let password = document.getElementById('passwordRegistro').value;
            let ubicacion = document.getElementById('ubicacionRegistro').value;
            
            if (estaVacio(nombre)) {
                mostrarError('nombreRegistro', 'errorNombreRegistro', 'El nombre es obligatorio');
                hayError = true;
            } else if (nombre.trim().length < 4) {
                mostrarError('nombreRegistro', 'errorNombreRegistro', 'El nombre debe tener al menos 4 caracteres');
                hayError = true;
            } else {
                limpiarError('nombreRegistro', 'errorNombreRegistro');
            }
            
            if (estaVacio(email)) {
                mostrarError('emailRegistro', 'errorEmailRegistro', 'El email es obligatorio');
                hayError = true;
            } else {
                limpiarError('emailRegistro', 'errorEmailRegistro');
            }
            
            if (estaVacio(password)) {
                mostrarError('passwordRegistro', 'errorPasswordRegistro', 'La contraseña es obligatoria');
                hayError = true;
            } else if (password.length < 6) {
                mostrarError('passwordRegistro', 'errorPasswordRegistro', 'La contraseña debe tener al menos 6 caracteres');
                hayError = true;
            } else {
                limpiarError('passwordRegistro', 'errorPasswordRegistro');
            }
            
            if (estaVacio(ubicacion)) {
                mostrarError('ubicacionRegistro', 'errorUbicacionRegistro', 'La ubicación es obligatoria');
                hayError = true;
            } else if (ubicacion.trim().length < 3) {
                mostrarError('ubicacionRegistro', 'errorUbicacionRegistro', 'La ubicación debe tener al menos 3 caracteres');
                hayError = true;
            } else {
                limpiarError('ubicacionRegistro', 'errorUbicacionRegistro');
            }
            
            if (hayError) {
                evento.preventDefault();
            }
        });
    }
    
    // Validar formulario de login
    let formularioLogin = document.getElementById('formLogin');
    
    if (formularioLogin) {
        formularioLogin.addEventListener('submit', function(evento) {
            let hayError = false;
            
            let email = document.getElementById('emailLogin').value;
            let password = document.getElementById('passwordLogin').value;
            
            if (estaVacio(email)) {
                mostrarError('emailLogin', 'errorEmailLogin', 'El email es obligatorio');
                hayError = true;
            } else {
                limpiarError('emailLogin', 'errorEmailLogin');
            }
            
            if (estaVacio(password)) {
                mostrarError('passwordLogin', 'errorPasswordLogin', 'La contraseña es obligatoria');
                hayError = true;
            } else {
                limpiarError('passwordLogin', 'errorPasswordLogin');
            }
            
            if (hayError) {
                evento.preventDefault();
            }
        });
    }
    
    // Limpiar errores al escribir (para todos los inputs)
    let campos = document.querySelectorAll('input');
    
    for (let i = 0; i < campos.length; i++) {
        let campo = campos[i];
        
        campo.addEventListener('input', function() {
            if (this.id.includes('nombre')) {
                let sufijo = this.id.includes('Registro') ? 'Registro' : (this.id.includes('Admin') ? 'Admin' : 'Normal');
                limpiarError(this.id, 'errorNombre' + sufijo);
            }
            if (this.id.includes('email')) {
                let sufijo = this.id.includes('Registro') ? 'Registro' : (this.id.includes('Admin') ? 'Admin' : (this.id.includes('Login') ? 'Login' : 'Normal'));
                limpiarError(this.id, 'errorEmail' + sufijo);
            }
            if (this.id.includes('password')) {
                let sufijo = this.id.includes('Registro') ? 'Registro' : (this.id.includes('Admin') ? 'Admin' : (this.id.includes('Login') ? 'Login' : 'Normal'));
                limpiarError(this.id, 'errorPassword' + sufijo);
            }
            if (this.id.includes('ubicacion')) {
                let sufijo = this.id.includes('Registro') ? 'Registro' : (this.id.includes('Admin') ? 'Admin' : 'Normal');
                limpiarError(this.id, 'errorUbicacion' + sufijo);
            }
            
        });
    }
});