var checkboxes = [];
$(document).ready(function () {
    document.cookie = "checkboxes=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    $('#btn-filter').on('click',function () {
        $('#filter-modal').modal('show');
    });

    $('#btn-clear').on('click',function () {
        validateFormSubmit();
        $.ajax({
            url: urlWorkList,
            type: 'post',
            dataType: 'html',
            success: function (result) {
                $('#data-work-list').html(result);
            }
        });
    });

    $('#filter-search-form').on('beforeSubmit',function () {
        var data = $('#filter-search-form').serializeArray();

        $.ajax({
            url: urlFilterSearch,
            type: 'post',
            dataType: 'html',
            data:data,
            success: function (result) {
                $('#data-work-list').html(result);
                $('#filter-modal').modal('hide');
            }
          });

        return false;

    });

    $('#btn-download-media').on('click',function () {
        var checkboxesStr = getCookie('checkboxes');
        console.log(checkboxes);
        if(checkboxesStr != "" && checkboxes.length > 0){
            checkboxes = removeDuplicateData(checkboxes);
            setCookie('checkboxes',checkboxes);
        }
        else if(checkboxesStr != ""){
            console.log(getCookie('checkboxes'));
        }
        else if(checkboxes.length > 0){
            checkboxes = removeDupliceCB(checkboxes);
            setCookie('checkboxes',checkboxes);
        }else{
            alert('Please choose item to download');
        }
        if(getCookie('checkboxes') != ""){
            $('#workArray').val(getCookie('checkboxes'));
            $('#id-work-form').submit();
            document.cookie = "checkboxes=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            // $.ajax({
            //     url: urlDownloadMedia,
            //     type: 'post',
            //     data:{
            //         'workId': getCookie('checkboxes')
            //     },
            //     success: function (result) {
            //         document.cookie = "checkboxes=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            //         console.log(result);
            //         window.location = result;
            //     }
            // });
        }
        checkboxes = [];
        //After success download set default data
        //console.log(deleteDuplicateWork());

    });

});


//set up checkbox for workId
function setCheckBox(){
    var checkboxesStr = getCookie('checkboxes'); //cleaned Cookie data
    var arr = checkboxesStr.split(",");
    $.each(arr,function (index,value) {
        var name = "cb-download-"+value;
        if(document.getElementsByName(name).length > 0)
        {
            document.getElementsByName(name)[0].checked = true;
        }
    })

}

function setCookieCheckboxes(checkboxes) {
    var currentCheckBoxes = getCookie('checkboxes');
    if(checkboxes.length > 0){
        if(currentCheckBoxes == ''){
            checkboxes = removeDupliceCB(checkboxes);
        }else{
            checkboxes = removeDuplicateData(checkboxes);// clean data for checkboxes and cookie
        }
        setCookie('checkboxes',checkboxes);
    }
}

function removeDuplicateData(checkboxes){
    var checkboxesStr = getCookie('checkboxes');
    var cookieArr = checkboxesStr.split(",");
    var arr = cookieArr.concat(checkboxes);
    var arrayTimes = {};
    for(var i=0;i<arr.length;i++){
        if(typeof arrayTimes[arr[i]] == "undefined"){
            arrayTimes[arr[i]]= 1;
        }else{
            arrayTimes[arr[i]]= arrayTimes[arr[i]]+1;
        }
    }
    return deleteDuplicateWork(arrayTimes);
}

//Get work id with number of times appears is odd
function deleteDuplicateWork(works) {
    var newArr = [];
    $.each(works,function (key,value) {
        if(value %2 != 0){
            newArr.push(key);
        }
    });
    return newArr;
}

function removeDupliceCB(arr){
    var arrayTimes = {};
    for(var i=0;i<arr.length;i++){
        if(typeof arrayTimes[arr[i]] == "undefined"){
            arrayTimes[arr[i]]= 1;
        }else{
            arrayTimes[arr[i]]= arrayTimes[arr[i]]+1;
        }
    }
    return deleteDuplicateWork(arrayTimes);
}

function setCookie(cname,cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + (0.5*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function validateFormSubmit(){
    var elements = document.getElementById("filter-search-form").elements;
    $.each(elements,function (index,value) {
        if(value.name !== '_csrf'){
            $(this).val('');
        }
    });
}
function startDateRequired(attribute, value){
    var endDate = $('input[name=\'FilterForm[endDate]\']').val();
    return endDate != '';
}
function startFinishTimeRequired(attribute, value){
    var endFinishTime = $('input[name=\'FilterForm[endFinishTime]\']').val();
    return endFinishTime != '';
}

function compareStartDate(attribute, value) {
    var startDate = $('input[name=\'FilterForm[startDate]\']').val();
    var endDate = $('input[name=\'FilterForm[endDate]\']').val()
    return startDate != '' && endDate != '';
}

function compareFinishDate(attribute,value){
    var start = $('input[name=\'FilterForm[startFinishTime]\']').val();
    var end = $('input[name=\'FilterForm[endFinishTime]\']').val()
    return start != '' && end != '';
}

function compareDate(attribute,value) {
    var start = $('input[name=\'FilterForm[startDate]\']').val();
    var end = $('input[name=\'FilterForm[startFinishTime]\']').val()
    return start != '' && end != '';
}

function compareDateFinish(attribute,value) {
    var startDate = $('input[name=\'FilterForm[startDate]\']').val();
    var endDate = $('input[name=\'FilterForm[endDate]\']').val();
    var startFinishDate = $('input[name=\'FilterForm[startFinishTime]\']').val();
    return startDate != '' && endDate != '' && startFinishDate != '';
}