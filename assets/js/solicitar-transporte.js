const PESSOA_FISICA = 'pessoa_fisica'
const PESSOA_JURIDICA = 'pessoa_juridica'

$(document).ready(function() {
    masks();
    $(document).on('change', '.tipo_dados_pessoais', function() {

        if (PESSOA_FISICA === $(this).val() && $(this).is(':checked')) {
            $('#pessoa_fisica_wrapper').show();
            $('#pessoa_juridica_wrapper').hide();
        }

        if (PESSOA_JURIDICA === $(this).val() && $(this).is(':checked')) {
            $('#pessoa_juridica_wrapper').show();
            $('#pessoa_fisica_wrapper').hide();
        }
    });

    $(document).on('change', '.tipo_origem', function() {
        const ORIGEM_LEVAR = "levar_veiculo"
        const ORIGEM_BUSCAR = "buscar_veiculo"

        if (ORIGEM_LEVAR === $(this).val() && $(this).is(':checked')) {
            $('#origem-levar').show();
            $('#origem-buscar').hide();
        }

        if (ORIGEM_BUSCAR === $(this).val() && $(this).is(':checked')) {
            $('#origem-levar').hide();
            $('#origem-buscar').show();
        }
    })

    $(document).on('change', '.tipo_destino', function() {
        const DESTINO_LEVAR = "levem_veiculo"
        const DESTINO_RETIRAR = "retirar_veiculo"

        if (DESTINO_LEVAR === $(this).val() && $(this).is(':checked')) {
            $('#destino-levar').show();
            $('#destino-retirar').hide();
        }

        if (DESTINO_RETIRAR === $(this).val() && $(this).is(':checked')) {
            $('#destino-levar').hide();
            $('#destino-retirar').show();
        }
    })

})

function masks() {
    $('.cpf').mask('000.000.000-00');
    $('.whatsapp').mask('(00) 00000-0000');
    $('.telefone-fixo').mask('(00) 00000-0000');

    $('.cnpj').mask('00.000.000/0000-00');
    $('.dtnasc-responsavel').mask('00/00/0000');


    $('.cep').mask('00000-000');
}