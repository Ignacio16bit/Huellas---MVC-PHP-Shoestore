var registerForm = document.getElementById('register-form');
var loginForm = document.getElementById('login-form');
var adminForm = document.getElementById('admin-form');

//Funcionalidades comunes
function validEmail(email){
    const emailRegex = /^[A-Za-z0-9.!#$%&'*+-/=¿?¡!^_{|}]+@[A-Za-z0-9-]+(?:\.[A-Za-z0-9-]+)*$/;
    return emailRegex.test(email);
};

function error(mensaje){
    alert(mensaje);
};

//Validación del login y envío a servidor
loginForm.addEventListener('submit', function(evento){
    evento.preventDefault();

    if (validarLogin()){
        this.submit();
        console.log('Formulario enviado');
    }
});
//Validación del registro
registerForm.addEventListener('submit', function (evento){
    evento.preventDefault();

    if (validarRegistro()){
        this.submit();
        console.log('Formulario enviado');
    }
});

//Validación de admin
adminForm.addEventListener('submit', function(evento){
    evento.preventDefault();
    if (validarRegistro()){
        this.submit();
        console.log('Admin creado con éxito');
    }
})

function validarLogin(){
    let email = document.getElementById('login-email').value;
    let password = document.getElementById('login-password').value;

    if (!email || !password) {
        error('Complete los campos requeridos');
        return false;
    };
    if (!validEmail(email)){
        error('El email no es válido');
        return false;
    };
    if (password.length<6){
        error('La contraseña debe incluir al menos 6 caracteres');
        return false;
    };
    return true;
};

function validarRegistro(){
    let nombre = document.getElementById('register-name').value;
    let email = document.getElementById('register-email').value;
    let password = document.getElementById('register-password').value;

    if (!email || !password ||!nombre) {
        error('Complete los campos requeridos');
        return false;
    };
    if (nombre.length<2){
        error('El nombre debe tener más de dos caracteres')
        return false;
    };
    if (!validEmail(email)){
        error('El email no es válido');
        return false;
    };
    if (password.length<6){
        error('La contraseña debe incluir al menos 6 caracteres');
        return false;
    };
    return true;
}