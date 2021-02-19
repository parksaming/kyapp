$('#toggle-password').click(function () {
    var passwordType = $('#password').attr('type');
    if(passwordType == "password"){
        $('#password').attr('type','text');
        $('#toggle-password').text(hide);
    }else{
        $('#password').attr('type','password');
        $('#toggle-password').text(show);
    }
});