$(document).ready(function () {
  const count = 0;
  $(document).on('click', '#input_update', function () {
    if (count < 1) {
      saveData()
      count++
    }
  })

  function saveData() {
    $('.loading').show();
    $('.background-load').show();
    const URL_SITE = $('#url_site').val();
    $.ajax({
      url: `${URL_SITE}/wp-json/user/save/note`,
      method: "POST",
      data: {
        id: $("#id-request-transport").val(),
        observacao: $("#observacao").val()
      },
      success: function (response) {
        if (response.status) {
          $('#message').text('Atualização realizada com sucesso!')
          $('#mediumModalLabel').text('Sucesso!')
          $('.modal').modal('show')
        } else {
          $('#message').text('Erro ao enviar a atualização da solicitação de transporte, tente novamente mais tarde')
          $('#mediumModalLabel').text('Error')
          $('.modal').modal('show')
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
})