$(document).ready(function () {
    $('.checkbox-group').on('click',function () {
        var checkBoxes = {};
        checkBoxes.organizationId = organizationId;
        $('.checkbox-group').each(function( index,element ) {
            checkBoxes[$(element).attr('name')] = $(element).prop('checked') ? 1:0;
        });
        $.pjax.reload({container: '#pjax-facility-company',url:urlUpdateData,data:checkBoxes,type:'GET'});
    });
});