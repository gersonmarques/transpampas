const PESSOA_FISICA = 'pessoa_fisica'
const PESSOA_JURIDICA = 'pessoa_juridica'

$(document).ready(function() {

    $(document).on('change', '.form-check-input', function() {

        if (PESSOA_FISICA === $(this).val() && $(this).is(':checked')) {
            $('#pessoa_fisica_wrapper').show();
            $('#pessoa_juridica_wrapper').hide();
        }

        if (PESSOA_JURIDICA === $(this).val() && $(this).is(':checked')) {
            $('#pessoa_juridica_wrapper').show();
            $('#pessoa_fisica_wrapper').hide();
        }
    });
})