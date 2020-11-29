const PESSOA_FISICA = 'pessoa_fisica'
const PESSOA_JURIDICA = 'pessoa_juridica'
const STEP1 = '1'
const STEP2 = '2'
const STEP3 = '3'

$(document).ready(function () {
  masks();

  $(document).on('change', '.tipo_dados_pessoais', function () {
    if (PESSOA_FISICA === $(this).val() && $(this).is(':checked')) {
      $('#pessoa_fisica_wrapper').show();
      $('#pessoa_juridica_wrapper').hide();
    }

    if (PESSOA_JURIDICA === $(this).val() && $(this).is(':checked')) {
      $('#pessoa_juridica_wrapper').show();
      $('#pessoa_fisica_wrapper').hide();
    }
  });

  $(document).on('change', '.tipo_origem', function () {
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

  $(document).on('change', '.tipo_destino', function () {
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

  $(document).on('click', '.btn-next', function (e) {
    e.preventDefault();
    if (!validate()) return false;

    const step = $('.active').attr('data-step')
    if (step === STEP1 || step === STEP2) {
      steps("next");
    }

    if (step === STEP3) {
      steps("send");
    }
  })

  $(document).on('click', '.btn-prev', function (e) {
    e.preventDefault();
    steps("prev");
  })

  $(document).on('blur', '.cep', function () {
    const CEP_LENGTH = 8;
    if ($(this).cleanVal().length < CEP_LENGTH) return;

    $('.loading').show();
    $('.background-load').show();
    getCEP($(this).cleanVal()).then((response) => {
      if (response) {
        $(this).parent().next().find('input[name="endereco"]').val(response.logradouro)
        $(this).parent().find('input[name="cidade"]').val(response.localidade)
        $(this).parent().find('input[name="bairro"]').val(response.bairro)
        $(this).parent().find(`select[name="estado"] option[value=${response.uf}]`).attr('selected', 'selected');
      }
    })
  })

  $(document).on('blur', '.cpf', function () {
    const CPF_LENGTH = 11;
    if ($(this).cleanVal().length < CPF_LENGTH) return;

    $('.loading').show();
    $('.background-load').show();
    getUserInfo($(this).cleanVal(), true)
  });

  $(document).on('blur', '.cnpj', function () {
    const CNPJ_LENGTH = 14;

    if ($(this).cleanVal().length < CNPJ_LENGTH) return;

    $('.loading').show();
    $('.background-load').show();
    getUserInfo($(this).cleanVal(), false)
  })
})

function masks() {
  $('.cpf').mask('000.000.000-00');
  $('.whatsapp').mask('(00) 00000-0000');
  $('.telefone-fixo').mask('(00) 00000-0000');

  $('.cnpj').mask('00.000.000/0000-00');
  $('.dtnasc-responsavel').mask('00/00/0000');

  $('.ano').mask('0000');
  $('.valor-fipe').mask('R$ 00.000,00')

  $('.cep').mask('00000-000');
}

function validate() {
  const fields = $('input,select').filter('[required]:visible').serializeArray();

  for (let field of fields) {
    if (field.name === "cpf") {
      field.value = $(`input[name='${field.name}']`).cleanVal();
      if (!validateCPF(field.value)) {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      }
    } else if (field.name === "cnpj") {
      field.value = $(`input[name='${field.name}']`).cleanVal();
      if (!validateCNPJ(field.value)) {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      }
    } else if (field.name === "nome") {
      if (!validateFullName(field.value)) {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      }
    } else if (field.name === "email") {
      if (!validateEmail(field.value)) {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      }
    } else {
      $(`input[name='${field.name}']`).removeClass('error')
    }

    if (!field.value) {
      if (field.name == "rg") {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      } else if (field.name == "whatsapp") {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      } else {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      }
    } else {
      field.value = checkedFieldMask(field);
      $(`input[name='${field.name}']`).removeClass('error')
    }
  }
  return true;
}


function checkedFieldMask(field) {
  if (
    field.name === "cpf" ||
    field.name === "whatsapp" ||
    field.name === "telefone-fixo" ||
    field.name === "cnpj" ||
    field.name === "cep"
  ) {
    return $(`input[name='${field.name}']`).cleanVal()
  }
  return field.value
}

function steps(type) {
  const step = $('.active')
  const step_active = $('.step-active')
  if (type == "next") {
    if (step.attr('data-step') === STEP1) {
      $('.btn-prev').show()

    }
    if (step.attr('data-step') === STEP2) {
      $(".btn-prev").show()
      $(".btn-next").text("Enviar")
    }
    step_active.next().addClass('step-active')
    step_active.removeClass('step-active')
    step.removeClass('active').addClass('passed')
    step.next().addClass('active')
  }
  if (type == "prev") {
    if (step.attr('data-step') === STEP2) {
      $(".btn-prev").hide()
    }
    if (step.attr('data-step') === STEP3) {
      $(".btn-prev").show()
      $(".btn-next").text("Próximo")
    }
    step_active.prev().addClass('step-active')
    step_active.removeClass('step-active')
    step.removeClass('active').removeClass('passed');
    step.prev().addClass('active').removeClass('passed')
  }

  if (type = "send") {

  }
}

function getUserInfo(field, iscpf) {
  const URL_SITE = $('#url_site').val();
  const data = iscpf ? { cpf: field } : { cnpj: field };

  $.ajax({
    url: `${URL_SITE}/wp-json/user/get/info`,
    method: "post",
    data: data,
    success: function (response) {

      if (!response.erro_code) {

        response.forEach(item => {
          if (item.meta_key == "user_dados_pessoais_rg") {
            $('.rg').val(item.meta_value);
          }

          if (item.meta_key == "user_contato_whatsapp") {
            $('.whatsapp').val(item.meta_value);
          }

          if (item.meta_key == "user_contato_telefone_fixo") {
            $('.telefone-fixo').val(item.meta_value);
          }

          if (item.meta_key == "user_contato_email") {
            $('.nome').val(item.display_name);
            $('.email').val(item.meta_value);
          }
        });
      }
    },
    fail: function (response) {
      return {}
    },
    complete: function (response) {
      $('.loading').hide();
      $('.background-load').hide();
    }
  })
}