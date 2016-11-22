$('.alert').alert();

$('#current_year').on('change', function() {
    console.log('test')
    $(this).parents('form').submit();
});