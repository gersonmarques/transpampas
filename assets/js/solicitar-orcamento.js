var STEP1 = '1'
var STEP2 = '2'
var STEP3 = '3'
var dataFields = {}
var hasUser = false
var dataCourtyards = []
var pessoaFisica = true;

$(document).ready(function () {
  masks();
  getCourtyards();


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
    // btn-next

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
    street.forEach(({street, city, number, state, neighborhood}) => {
      const streetText = `${street}, ${number}, ${neighborhood} - ${city} - ${state}`
      endereco.append(new Option(streetText, street))
    });

    if (street.length > 0) {
      endereco.prop('disabled', false);
    } else {
      endereco.prop('disabled', true);
    }
  })

})

function masks() {
  $('.whatsapp').mask('(00) 00000-0000');
  $('.telefone-fixo').mask('(00) 00000-0000');

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
    if (field.name === "nome") {
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
      if (field.name == "whatsapp") {
        $(`input[name='${field.name}']`).addClass('error').focus();
        return false;
      } else {
        $(`input[name='${field.name}']`).addClass('error').focus();
        $(`select[name='${field.name}']`).addClass('error').focus();
        return false;
      }
    } else {
      field.value = checkedFieldMask(field);
      $(`input[name='${field.name}']`).removeClass('error')

      if ($(`input[name='${field.name}']`).parent().parent().attr('id') === "origem-levar") {
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
    field.name === "whatsapp" ||
    field.name === "telefone-fixo" ||
    field.name === "origem-cep" ||
    field.name === "destino-cep"
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
  $('#form-content-top')[0].scrollIntoView();
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
  const params = $('.telefone-fixo').val() ? {
    ...data,
    orcamento: 1,
    id: $('#id').val(),
    telefone_fixo: $('.telefone-fixo').val(),
  } : {
    ...data,
    orcamento: 1,
    id: $('#id').val(),
  };

  $.ajax({
    url: `${URL_SITE}/wp-json/user/save`,
    method: "POST",
    data: params,
    success: function (response) {
      if (response.status) {
        $('#form-solicitar-transporte').hide();
        $("#html-success").show();
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