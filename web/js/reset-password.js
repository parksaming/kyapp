$( document ).ready(function() {
    //Reset password
    if(isValidateSuccess == 1){
        $('#send-mail-modal').modal('show');
    }
    $('#btn-cancel').on('click',function () {
        $('#send-mail-modal').modal('hide');
    });
    $('#btn-send').on('click',function () {
        $('#send-mail-modal').modal('hide');
        $.ajax({
            url: urlSendMail,
            type: 'GET',
            data:{
                'userCode' : $('#reset-userCode').val()
            },
            success: function (response) {
                if(response == false){
                    console.log('Save not successfully');
                }else{
                    $('#check-mail-modal').modal('show');
                }
            }
        });
      //  $('#check-mail-modal').modal('show');
    });
    $('#btn-close-mail').on('click',function () {
        $('#check-mail-modal').modal('hide');
    });
   
});