$(document).ready(function(){
    $('#login-link').click(function(e){
        e.preventDefault();
        $('#loginModal').modal('show');
    });

    $('#register-link').click(function(e){
        e.preventDefault();
        $('#registerModal').modal('show');
        $('#registerModal').modal('setting','closable', true);
    });

    $('.ui.checkbox').checkbox();
});