import './bootstrap';
// Require jQuery (já incluído no StarAdmin)
window.$ = window.jQuery = require('jquery');

// Inicializar tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

// Mostrar/ocultar alertas
$('.alert').delay(3000).fadeOut('slow');

// Validação de formulários
$('form.needs-validation').on('submit', function(event) {
    if (this.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
    }
    $(this).addClass('was-validated');
});
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};