var Budget = function () {
  Budget.prototype.send = function (arrayFields, url) {
    var requestTransport = new Budget();
    var params = url.split('?');
    params = params[params.length - 1].split('&')
    var tasks = params[0].split('=');
    tasks = tasks[tasks.length - 1];
    console.log(jQuery)
    var post = jQuery.ajax({
      url: url,
      type: "POST",
      dataType: "JSON",
      async: false,
      data: {
        ...arrayFields
      },
      success: function (result) {
        if (jQuery(".notice").hasClass("notice-error")) {
          jQuery('.notice').removeClass('notice-error');
          if (!jQuery(".notice").hasClass("notice-success")) {
            jQuery('.notice').addClass('notice-success');
          }
        } else {
          if (!jQuery(".notice").hasClass("notice-success")) {
            jQuery('.notice').addClass('notice-success');
          }
        }

        switch (tasks) {
        case 'query_request_transport':
          jQuery('.list-request-transport > tbody').html(requestTransport.html_list(result));
          jQuery('.loader-search').css('display', 'none');
          return result;
          break;
        case 'deleteRequest':
          var search = jQuery('#query-request-transport').val();
          var filter = jQuery('#param-request-transport').val();
          jQuery('.loader-search').css('display', 'none');
          requestTransport.query(search, filter);
          break;
        case 'addPatio':
          requestTransport.query();
          jQuery('#name-requestTransport').val("");
          jQuery('#description-requestTransport').val("");
          break;
        case 'updateRequest':
          break;
        }

        if (tasks === "query_request_transport") {
          return;
        }


        if (!result) {
          var text = requestTransport.textReturn(tasks);
          jQuery('.notice').removeClass('notice-success');
          jQuery('.notice').addClass('notice-error');
          jQuery('.notice > p > strong').text(text['error']);
          jQuery('.notice').show();
          jQuery(".notice").attr("tabindex", -1).focus();
          jQuery('.loader').hide();
        } else {
          var success = requestTransport.textReturn(tasks);
          jQuery('.notice > p > strong').text(success['success']);
          jQuery('.notice').show();
          jQuery(".notice").attr("tabindex", -1).focus();
          jQuery('.loader').hide();
        }


        if (tasks === "updateRequest") {
          setInterval(() => {
            location.reload();
          }, 2000)
        }
      },
      error: function (result) {
        var text = requestTransport.textReturn(tasks);
        jQuery('.notice').removeClass('notice-success');
        jQuery('.notice').addClass('notice-error');
        jQuery('.notice > p > strong').text(text['error']);
        jQuery('.notice').show();
        jQuery(".notice").attr("tabindex", -1).focus();
        jQuery('.loader').hide();
      },
    });
    return post;
  }

  Budget.prototype.textReturn = function (type) {
    switch (type) {
    case 'addPatio':
      var arrayReturn = {
        "success": "Solicitação de Orçamento salvo com sucesso !",
        "error": "Erro ao salvar a pátio, tente novamente."
      }
      return arrayReturn;
      break;
    case 'updateRequest':
      var arrayReturn = {
        "success": "Solicitação de Orçamento atualizado com sucesso !",
        "error": "Erro ao editar os dados, tente novamente."
      }
      return arrayReturn;
      break;
    case 'deleteRequest':
      var arrayReturn = {
        "success": "Solicitação excluída com sucesso !",
        "error": "Erro ao excluir a solicitação, tente novamente."
      }
      return arrayReturn;
      break;
    }
  }

  Budget.prototype.query = function (search, filter) {
    var url = '../wp-content/plugins/transporte/src/budget/budget.php';
    var task = 'task=query_request_transport';
    var requestTransport = new Budget();
    if (typeof (search) === "undefined") {
      return this.send('', url + "?" + task);
    }

    var query = {
      search: search,
      filter: filter
    }

    return this.send(query, url + "?" + task);
  }

  Budget.prototype.html_list = function (data) {
    var html = "";

    jQuery.each(data, function (index, value) {
      const cpfOrCnpj = (value.cpf) ? value.cpf : value.cnpj
      const criado = new Date(value.criado);
      const modificado = new Date(value.modificado);

      const status = [
        'Aguardando',
        'Em andamento',
        'Concluído',
        'Fechado'
      ]

      html += `
      <tr>;
        <td><input type="checkbox" name="checkbox-actions" class="checkbox-actions" value="${value.id}"></td>;
        <td style="text-align: center;">${value.id}</td>;
        <td>${value.nome || "" }</td>;
        <td>${value.email || "" }</td>;
        <td>${status[value.status]}</td>;
        <td>${criado.toLocaleDateString("pt-BR")}</td>;
        <td>${modificado.toLocaleDateString("pt-BR")}</td>;
      </tr>`;
    });
    return html;
  }


  Budget.prototype.update = function () {

    var arrayFields = {
      id: jQuery('#id-request-transport').val(),
      modelo_veiculo: jQuery('#modelo-veiculo-request-transport').val(),
      ano_veiculo: jQuery('#ano-veiculo-request-transport').val(),
      fipe_veiculo: jQuery('#fipe-request-transport').val(),
      situacao_veiculo: jQuery('#situacao-veiculo-request-transport').val(),
      cor_veiculo: jQuery('#cor-request-transport').val(),
      placa_veiculo: jQuery('#placa-request-transport').val(),
    };

    var validation = new Validation();

    if (!validation.fields(arrayFields)) {
      jQuery('.loader').hide();
      jQuery('.notice').removeClass('notice-success');
      jQuery('.notice').addClass('notice-error');
      jQuery('.notice > p > strong').text("Campos obrigatórios não foram preenchidos corretamente.");
      jQuery('.notice').show();
      return false;
    }
    arrayFields['observacao'] = jQuery('#observacao').val()
    arrayFields['status'] = jQuery('#status').val()

    var url = '../wp-content/plugins/transporte/src/budget/budget.php';
    var task = 'task=updateRequest';
    return this.send(arrayFields, url + "?" + task);
  }

  Budget.prototype.delete = function () {
    var arrayFields = {
      id: jQuery('.checkbox-actions:checked').val(),
    };
    var url = '../wp-content/plugins/transporte/src/budget/budget.php';
    var task = 'task=deleteRequest';
    return this.send(arrayFields, url + "?" + task);
  }
}