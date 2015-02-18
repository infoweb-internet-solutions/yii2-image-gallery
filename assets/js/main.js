$(function() {

    $('#blueimp-gallery').data({
        fullScreen: false
    });

    $('#input-id').on('fileimageloaded', function(event, previewId) {
        console.log("fileimageloaded");
    });


    $('#test').on('filebatchuploadsuccess fileuploaded', function(event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;

        $.pjax.reload({container: '#grid-pjax'});
    });

});