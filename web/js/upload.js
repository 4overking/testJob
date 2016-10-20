$('#form_attachment').change(function(){
    $(this).parents('form:first').submit();
});
$('#upload-button-trigger').click(function () {
    $('#form_attachment').trigger('click');
});
