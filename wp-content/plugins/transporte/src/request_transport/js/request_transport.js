var RequestTransport = function () {
  RequestTransport.prototype.save = function () {
    let cep = jQuery('#cep-courtyards').val()

    var arrayFields = {
      title: jQuery('#title-courtyards').val(),
      cep: cep.replace(/\D/, ''),
      street: jQuery('#street-courtyards').val(),
      number: jQuery('#number-courtyards').val(),
      neighborhood: jQuery('#neighborhood-courtyards').val(),
      city: jQuery('#city-courtyards').val(),
      state: jQuery('#state-courtyards').val(),
      reference: jQuery('#reference-courtyards').val(),
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
    if (jQuery('#reference-courtyards').val() != "" && jQuery('#reference-courtyards').val() != "undefined" && jQuery('#reference-courtyards').val() != null) {
      arrayFields['reference'] = jQuery('#reference-courtyards').val();
    }

    var url = '../wp-content/plugins/transporte/src/courtyards/courtyards.php';
    var task = 'task=addPatio';
    return this.send(arrayFields, url + "?" + task);
  }

  RequestTransport.prototype.send = function (arrayFields, url, hasHeaders = false) {
    var requestTransport = new RequestTransport();
    var params = url.split('?');
    params = params[params.length - 1].split('&')
    var tasks = params[0].split('=');
    tasks = tasks[tasks.length - 1];
    headers = hasHeaders ? {
      contentType: false,
      processData: false,
    } : {}

    var post = jQuery.ajax({
      url: url,
      type: "POST",
      dataType: "JSON",
      async: false,
      ...headers,
      data: arrayFields,
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
          requestTransport.query();
          break;
        case 'addPatio':
          requestTransport.query();
          jQuery('#name-requestTransport').val("");
          jQuery('#description-requestTransport').val("");
          break;
        case 'updatePatio':
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
      },
      error: function (result) {
        var text = courtyards.textReturn(tasks);
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

  RequestTransport.prototype.textReturn = function (type) {
    switch (type) {
    case 'addPatio':
      var arrayReturn = {
        "success": "Solicitação de Transporte salvo com sucesso !",
        "error": "Erro ao salvar a pátio, tente novamente."
      }
      return arrayReturn;
      break;
    case 'updateRequest':
      var arrayReturn = {
        "success": "Solicitação de Transporte atualizado com sucesso !",
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

  RequestTransport.prototype.query = function (search, filter) {
    var url = '../wp-content/plugins/transporte/src/request_transport/request_transport.php';
    var task = 'task=query_request_transport';

    if (typeof (search) === "undefined") {
      return this.send('', url + "?" + task);
    }

    var query = {
      search: search,
      filter: filter
    }
    return this.send(query, url + "?" + task);
  }

  RequestTransport.prototype.html_list = function (data) {
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

      html += '<tr>';
      html += '<td><input type="checkbox" name="checkbox-actions" class="checkbox-actions" value="' + value.id + '"></td>';
      html += '<td style="text-align: center;">' + value.id + '</td>';
      html += '<td>' + value.nome + '</td>';
      html += '<td>' + value.email + '</td>';
      html += '<td>' + cpfOrCnpj + '</td>';
      html += '<td>' + status[value.status] + '</td>';
      html += '<td>' + criado.toLocaleDateString("pt-BR") + '</td>';
      html += '<td>' + modificado.toLocaleDateString("pt-BR") + '</td>';
      html += '</tr>';
    });
    return html;
  }


  RequestTransport.prototype.update = function () {

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

    var crlv_file = jQuery('#crlv').prop('files')[0];
    var cnh_file = jQuery('#rg_cnh').prop('files')[0];
    var form_data = new FormData();

    form_data.append('cnh_rg', cnh_file);
    form_data.append('crlv', crlv_file);

    for (var key in arrayFields) {
      form_data.append(key, arrayFields[key]);
    }

    var url = '../wp-content/plugins/transporte/src/request_transport/request_transport.php';
    var task = 'task=updateRequest';
    return this.send(form_data, url + "?" + task);
  }

  RequestTransport.prototype.delete = function () {
    var arrayFields = {
      id: jQuery('.checkbox-actions:checked').val(),
    };
    var url = '../wp-content/plugins/transporte/src/request_transport/request_transport.php';
    var task = 'task=deleteRequest';
    return this.send(arrayFields, url + "?" + task);
  }
}