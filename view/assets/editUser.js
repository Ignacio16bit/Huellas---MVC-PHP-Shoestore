function editUser(id, name, email){
    //Abrir modal con el formulario relleno
    //Los argumentos que recoge en cada dato del foreach los pasa a los valores de cada formulario
    document.getElementById('edit-user-id').value = id;
    document.getElementById('user_name').value = name;
    document.getElementById('user_email').value = email;

    $('#edit-user-modal').modal('show');
}

function deleteUser(id, name){
    document.getElementById('uID').value = id;
    document.getElementById('uName').placeholder = name;

    if(!id||!name){
        console.error('No hay elementos de usuario recogidos');
    }

    $('#rm-user-modal').modal('show');
}

//Funciones de usuario en perfil (TRABAJA CON $_SESSION PHP)
function editName(){
    $('#name-modal').modal('show');
}

function editEmail(){
    $('#email-modal').modal('show');
}
function rmUser(){
    $('#rm-modal').modal('show');
}

function changePass(){
    $('#pass-modal').modal('show');
}