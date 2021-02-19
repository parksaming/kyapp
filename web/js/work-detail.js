$(document).ready(function () {
    //play audio
    $('#confirm-audio').on('click',function () {
        $('#audio-file')[0].play();
    });
    $('#btn-accept').on('click',function () {
        $('#modal-confirm').modal('show');
    });
    $('#btn-decline').on('click',function () {
        $('#modal-decline').modal('show');
    });
    $('#btn-not-confirm').on('click',function () {
        $('#modal-confirm').modal('hide');
    });
    $('#btn-not-decline').on('click',function () {
        $('#modal-decline').modal('hide');
    });
    //Confirm work
    $('#btn-confirm').on('click',function () {
        var data = {
            'officeComment' : $('#confirm-comment').val(),
            'isConfirm' : 1,
            'csrf' : csrf
        };
        $.ajax({
            url: urlUpdateStatusWork,
            type: 'post',
            data: {
               data:data
            },
            success: function (response) {
                $('#modal-confirm').modal('hide');
                if(response == true){

                    $('#btn-accept').attr('disabled',true);
                    $('#btn-decline').attr('disabled',true);
                }else{
                    console.log('Error');
                }

            }
        });
    });
    //Decline work
    $('#btn-decline').on('click',function () {
        var data = {
            'officeComment' : $('#confirm-comment').val(),
            'isConfirm' : 1,
            'csrf' : csrf
        };
        $.ajax({
            url: urlUpdateStatusWork,
            type: 'post',
            data: {
               data:data
            },
            success: function (response) {
                $('#modal-decline').modal('hide');
                if(response == true){
                    $('#btn-accept').attr('disabled',true);
                    $('#btn-decline').attr('disabled',true);
                }else{
                    console.log('Error');
                }

            }
        });
    });
    // var images = $('#work-image #image').find('img');
    $('#fullsize-image').height($('#main-content').height());
    $('#fullsize-image').width($('#main-content').width());
    var maxWidth = $('#fullsize-image').width();
    var maxHeigth = $('#fullsize-image').height();
    $('.image-right').on('click',function () {
        var imagePath = $(this).find('img').attr('src');
        var element = $('#fullsize-image img');
        var image = $(this).find('img');
        $(element).attr('src',imagePath);
        calculateRatio(element,image.width(),image.height());
        $('#fullsize-image').css('display','block');
    });

    function calculateRatio(element,imageWidth,imageHeight){
        var ratio = imageWidth/imageHeight;
        var newImageHeight = maxWidth/ ratio;
        var newImageWidth= imageWidth;
        if(newImageHeight > maxHeigth){
            newImageHeight = maxHeigth;
            newImageWidth = maxHeigth*ratio;
        }
        $(element).attr('width', newImageWidth);
        $(element).attr('height',newImageHeight);

    }
    $('#test').on('click',function () {
        $('#modal-image').modal('show');
    });
    if(!canConfirmWork){
        $('#btn-decline').css('visibility','hidden');
        $('#btn-accept').css('visibility','hidden');
    }
});

