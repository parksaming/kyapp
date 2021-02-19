$(document).ready(function () {
    //Setting password
    if(validateSettingForm == 1){
        $('#confirm-change-pwd-modal').modal('show');
    }
    $('#btn-change-cancel').on('click',function () {
        $('#confirm-change-pwd-modal').modal('hide');
    });


    $('#btn-change-pwd').on('click',function () {
        $('#confirm-change-pwd-modal').modal('hide');
        //ajax success then show notice modal
        $.ajax({
            url: urlChangePassword,
            type: 'POST',
            data:{
                'password':$('#setting-pwd').val(),
                'csrf' : csrf
            },
            success: function (response) {
                if(response == false){
                    console.log(response);
                }else{
                    console.log('Success');
                    $('#change-pwd-notice').modal('show');
                }
            }
        });
      //  $('#change-pwd-notice').modal('show');
    });
    $('#btn-login').on('click',function () {
       window.location.href = urlLogin;
    });
    $('.password-mask').on('click',function () {
        var setType = $(this).siblings('input').attr('type')== 'password' ? 'text' : 'password';
        setType == 'password' ? $(this).text(show) : $(this).text(hide);
        $(this).siblings('input').attr('type',setType);
    });
});