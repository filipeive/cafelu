// Inicialização de plugins
$(document).ready(function() {
    // Tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Popovers
    $('[data-toggle="popover"]').popover();
    
    // Select2
    $('.select2').select2();
    
    // Datepicker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
    });
});

// Tratamento de mensagens Toastr
window.showToast = function(type, message, title = '') {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "5000"
    };
    
    toastr[type](message, title);
};

// Tratamento de erros de formulário
window.displayErrors = function(errors) {
    $.each(errors, function(field, messages) {
        let input = $('[name="' + field + '"]');
        input.addClass('is-invalid');
        input.after('<div class="invalid-feedback">' + messages.join('<br>') + '</div>');
    });
};