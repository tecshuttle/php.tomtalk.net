/*
$('#fileupload').fileupload({
    dataType: 'json',
    add: function (e, data) {
        data.submit();
    },
    done: function (e, data) {

        $.each(data.result.files, function (index, file) {
            $('<p/>').text(file.name).appendTo($('#uploads_msg'));
        });
    },
    formData: [
        {
            name: 'type_id',
            value: 0
        }
    ],
    progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .bar').css('width', progress + '%');
    }
});
*/


//end file