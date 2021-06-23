const PESSOA_FISICA = 'pessoa_fisica'
const PESSOA_JURIDICA = 'pessoa_juridica'
const STEP1 = '1'
const STEP2 = '2'
const STEP3 = '3'
let dataFields = {}
let hasUser = false
let dataCourtyards = []
let pessoaFisica = true;

$(document).ready(function () {
  masks();
  getCourtyards()
  $(document).on('change', '.tipo_dados_pessoais', function () {
    if (PESSOA_FISICA === $(this).val() && $(this).is(':checked')) {
      $('#pessoa_fisica_wrapper').show();
      $('#pessoa_juridica_wrapper').hide();
      pessoaFisica = true;
    }

    if (PESSOA_JURIDICA === $(this).val() && $(this).is(':checked')) {
      $('#pessoa_juridica_wrapper').show();
      $('#pessoa_fisica_wrapper').hide();
      pessoaFisica = false;
    }
  });

  $(document).on('change', 'input, select', function () {
    $(this).removeClass('error')
  })

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
      saveData(dataFields)
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
        $(this).parent().next().find('.endereco').val(response.logradouro)
        $(this).parent().find('.cidade').val(response.localidade)
        $(this).parent().find('.bairro').val(response.bairro)
        $(this).parent().find(`.estado option[value=${response.uf}]`).attr('selected', 'selected');
      }
    })
  })

  $(document).on('blur', '.cpf', function () {
    const CPF_LENGTH = 11;
    if ($(this).cleanVal().length < CPF_LENGTH || !$(this).cleanVal()) return;

    $('.loading').show();
    $('.background-load').show();
    getUserInfo($(this).cleanVal(), true)
  });

  $(document).on('blur', '.cnpj', function () {
    const CNPJ_LENGTH = 14;

    if ($(this).cleanVal().length < CNPJ_LENGTH || !$(this).cleanVal()) return;

    $('.loading').show();
    $('.background-load').show();
    getUserInfo($(this).cleanVal(), false)
  })

  $(document).on('click', '.rg_cnh_label', function () {
    $('.rg_cnh').click()
  });

  $(document).on('click', '.crlv_label', function () {
    $('.crlv').click()
  });

  $(document).on('change', '.rg_cnh', function () {
    if (!this.files[0]) {
      $(this).text('Cópia/Foto legível de RG OU CNH (.pdf .jpg .png)')
      $(this).siblings('i').hide()
    }
    $('.rg_cnh_label').text(this.files[0].name)
    $(this).siblings('i').show()
  });

  $(document).on('change', '.crlv', function () {
    if (!this.files[0]) {
      $(this).text('Cópia/Foto legível de CRLV (.pdf .jpg .png)')
      $(this).siblings('i').hide()
    }
    $('.crlv_label').text(this.files[0].name)
    $(this).siblings('i').show()
  });

  $(document).on('change', '#origem-levar select[name="origem-estado"], #destino-retirar select[name="destino-estado"]', function () {
    const cidade = $(this).siblings('.cidade')
    cidade.empty().append(new Option('Cidade', ''))
    const cities = dataCourtyards.filter(item => item.state === this.value)
    cities.forEach(element => {
      cidade.append(new Option(element.city, element.city))
    });

    if (cities.length > 0) {
      cidade.prop('disabled', false);
    } else {
      cidade.prop('disabled', true);

      $(this).parent()
        .siblings('.endereco')
        .prop('disabled', true)
        .empty()
        .append(new Option('Endereço', ''));
    }
  })

  $(document).on('change', '#origem-levar select[name="origem-cidade"], #destino-retirar select[name="destino-cidade"]', function () {
    const endereco = $(this).parent().siblings('.endereco')
    const state = $(this).siblings('.estado').val()

    endereco.empty().append(new Option('Endereço', ''))
    const street = dataCourtyards.filter(item => item.state === state && item.city === this.value)
    street.forEach(element => {
      endereco.append(new Option(element.street, element.street))
    });

    if (street.length > 0) {
      endereco.prop('disabled', false);
    } else {
      endereco.prop('disabled', true);
    }
  })

  $(document).on('click', '.wrapper>i', function () {
    $(this).siblings('input').val('')
    $(this).siblings('.rg_cnh_label').text('Cópia/Foto legível de RG OU CNH (.pdf .jpg .png)')
    $(this).siblings('.crlv_label').text('Cópia/Foto legível de CRLV (.pdf .jpg .png)')
    $(this).hide()
  })
})

function masks() {
  $('.cpf').mask('000.000.000-00');
  $('.whatsapp').mask('(00) 00000-0000');
  $('.telefone-fixo').mask('(00) 00000-0000');

  $('.cnpj').mask('00.000.000/0000-00');
  $('.dtnasc-responsavel').mask('00/00/0000');

  $('.ano').mask('0000');
  $('.valor-fipe').mask('###.###,00', {
    reverse: true,
    placeholder: "R$ 30.000,00"
  })

  $('.cep').mask('00000-000');
}

function validate() {
  const fields = $('input, select').filter('[required]:visible').serializeArray();
  let aux = {}
  for (let field of fields) {
    if (field.name === "cpf") {
      field.value = $(`input[name='${field.name}']:visible`).cleanVal();
      if (!validateCPF(field.value)) {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      }
    } else if (field.name === "cnpj") {
      field.value = $(`input[name='${field.name}']:visible`).cleanVal();
      if (!validateCNPJ(field.value)) {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      }
    } else if (field.name === "nome") {
      if (!validateFullName(field.value)) {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      }
    } else if (field.name === "email") {
      if (!validateEmail(field.value)) {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      }
    } else {
      $(`input[name='${field.name}']:visible`).removeClass('error')
    }

    if (!field.value) {
      if (field.name == "rg") {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      } else if (field.name == "whatsapp") {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      } else {
        $(`input[name='${field.name}']:visible`).addClass('error').focus();
        $(`select[name='${field.name}']:visible`).addClass('error').focus();
        return false;
      }
    } else {
      field.value = checkedFieldMask(field);
      $(`input[name='${field.name}']:visible`).removeClass('error')

      if ($(`input[name='${field.name}']:visible`).parent().parent().attr('id') === "origem-levar") {
        aux[`origem-levar-${field.name}`] = field.value
      } else {
        aux[field.name] = field.value
      }
    }
  }

  if (Object.keys(aux).length) {
    dataFields = {
      ...dataFields,
      ...aux,
      hasUser,
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
    field.name === "origem-cep" ||
    field.name === "destino-cep"
  ) {
    return $(`input[name='${field.name}']:visible`).cleanVal()
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
  $('#form-content-top')[0].scrollIntoView();
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
        if (response.length === 0) {
          hasUser = false;
          $('.exists_user_cpf').hide();
          $('.exists_user_cnpj').hide();
          $('.rg').val("");
          $('.rg').prop('disabled', false);
          $('.whatsapp').val("");
          $('.whatsapp').prop('disabled', false);
          $('.telefone-fixo').val("");
          $('.telefone-fixo').prop('disabled', false);
          $('.nome').val("");
          $('.nome').prop('disabled', false);
          $('.email').val("");
          $('.email').prop('disabled', false);

          if (iscpf) {
            $('.cnpj').val("");
          } else {
            $('.cpf').val("");
          }
          return;
        }

        hasUser = true;
        if (iscpf) {
          $('.exists_user_cpf').show();
        } else {
          $('.exists_user_cnpj').show();
        }
        let id = 0;
        response.forEach(item => {
          if (iscpf) {
            if (item.meta_key == "user_dados_pessoais_rg") {
              $('.rg').val(item.meta_value);
              $('.rg').prop('disabled', true);
            }

            $('.inscricao-estadual').val("")
            $('.inscricao-estadual').prop('disabled', false);
            $('input[name="razao-social"]').val("");
            $('input[name="nome-responsavel"]').val("");
            $('.dtnasc-responsavel').val("");
            $('.cnpj').val("");
          } else {
            if (item.meta_key == "user_dados_pessoais_ie") {
              $('.inscricao-estadual').val(item.meta_value);
              $('.inscricao-estadual').prop('disabled', true);
            }
          }

          if (item.meta_key == "user_contato_whatsapp") {
            $('.whatsapp').val(item.meta_value);
            $('.whatsapp').prop('disabled', true);
          }

          if (item.meta_key == "user_contato_telefone_fixo") {
            $('.telefone-fixo').val(item.meta_value);
            $('.telefone-fixo').prop('disabled', true);
          }

          $('.nome').val(item.display_name);
          $('.email').val(item.email);
          $('.nome').prop('disabled', true);
          $('.email').prop('disabled', true);
          id = item.id;
        });
        getUserMeta(id);
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

function getCourtyards() {
  const URL_SITE = $('#url_site').val();

  $.ajax({
    url: `${URL_SITE}/wp-json/courtyards/get/`,
    method: "POST",
    success: function (response) {
      dataCourtyards = response
      const selectOrigem = $('#origem-levar select[name="origem-estado"]')
      const selectDestino = $('#destino-retirar select[name="destino-estado"]')
      getState(selectOrigem, response)
      getState(selectDestino, response)

    },
    fail: function (response) {
      return []
    },
    complete: function (response) {
      $('.loading').hide();
      $('.background-load').hide();
    }
  })
}

function getState(el, data) {
  const state = [new Option("Estado", "")];
  const ufs = el.find('option')


  Object.keys(ufs).forEach((element, index) => {
    if (ufs[index]) {
      data.forEach((item) => {
        if (element && item.state.toUpperCase() === ufs[index].value.toUpperCase()) {
          state.push(ufs[index])
          return;
        }
      })
    }
  })
  el.empty()
  el.append(state)
}

function saveData(data) {
  $('.loading').show();
  $('.background-load').show();
  const URL_SITE = $('#url_site').val();
  var crlv_file = $('#crlv').prop('files')[0];
  var cnh_file = $('#rg_cnh').prop('files')[0];
  var form_data = new FormData();
  const type_account = pessoaFisica ? "pessoa_fisica" : "pessoa_juridica"

  form_data.append('cnh_rg', cnh_file);
  form_data.append('crlv', crlv_file);
  form_data.append('id', $('#id').val());
  form_data.append('telefone_fixo', $(`#${type_account}_wrapper .telefone-fixo`).val() ? $(`#${type_account}_wrapper .telefone-fixo`).cleanVal() : '');
  form_data.append('type_account',type_account );

  for (var key in data) {
    form_data.append(key, data[key]);
  }

  $.ajax({
    url: `${URL_SITE}/wp-json/user/save`,
    method: "POST",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response.status) {
        $('#form-solicitar-transporte').hide();
        $('#html-success').show();
      } else {
        alert('Erro ao enviar a solicitação de transporte, tente novamente mais tarde');
      }
    },
    fail: function (response) {
      return []
    },
    complete: function (response) {
      $('.loading').hide();
      $('.background-load').hide();
    }
  })
}

function getUserMeta(id) {
  $('.loading').show();
  $('.background-load').show();
  const URL_SITE = $('#url_site').val();

  $.ajax({
    url: `${URL_SITE}/wp-json/user/getUserMeta`,
    method: "GET",
    data: { id },
    async: false,
    success: function (response) {
      $('#endereco-user-buscar .cep').val(response.user_endereco_cep).prop('disabled', true);
      $('#endereco-user-buscar .cidade').val(response.user_endereco_cidade).prop('disabled', true);
      $('#endereco-user-buscar .endereco').val(response.user_endereco_rua).prop('disabled', true);
      $('#endereco-user-buscar .numero').val(response.user_endereco_numero).prop('disabled', true);
      $('#endereco-user-buscar .bairro').val(response.user_endereco_bairro).prop('disabled', true);
      $('#endereco-user-buscar').find(`.estado option[value=${response.user_endereco_estado}]`).attr('selected', 'selected');
      $('#endereco-user-buscar .estado').prop('disabled', true);
    },
    fail: function (response) {
      $('#endereco-user-buscar .cep').val('').prop('disabled', false);
      $('#endereco-user-buscar .cidade').val('').prop('disabled', false);
      $('#endereco-user-buscar .endereco').val('').prop('disabled', false);
      $('#endereco-user-buscar .numero').val('').prop('disabled', false);
      $('#endereco-user-buscar .bairro').val('').prop('disabled', false);
      $('#endereco-user-buscar .estado').prop('disabled', false);

      return []
    },
    complete: function (response) {
      $('.loading').hide();
      $('.background-load').hide();
    }
  })
}