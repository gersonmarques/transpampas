var Courtyards = function () {
  Courtyards.prototype.save = function () {
    let cep = jQuery('#cep-courtyards').val()

    var arrayFields = {
      title: jQuery('#title-courtyards').val(),
      cep: cep.replace(/\D/, ''),
      street: jQuery('#street-courtyards').val(),
      number: jQuery('#number-courtyards').val(),
      neighborhood: jQuery('#neighborhood-courtyards').val(),
      city: jQuery('#city-courtyards').val(),
      state: jQuery('#state-courtyards').val(),
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

  Courtyards.prototype.send = function (arrayFields, url) {
    var courtyards = new Courtyards();
    var params = url.split('?');
    params = params[params.length - 1].split('&')
    var tasks = params[0].split('=');
    tasks = tasks[tasks.length - 1];

    var post = jQuery.ajax({
      url: url,
      type: "POST",
      dataType: "JSON",
      async: false,
      data: arrayFields,
      success: function (result) {
        console.log('success');
        console.log(result);
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
        case 'query_patios':
          jQuery('.list-courtyards > tbody').html(courtyards.html_list(result));
          jQuery('.loader-search').css('display', 'none');
          return result;
          break;
        case 'deletePatio':
          courtyards.query();
          break;
        case 'addPatio':
          courtyards.query();
          jQuery('#name-courtyards').val("");
          jQuery('#description-courtyards').val("");
          break;
        case 'updatePatio':
          break;
        }
        if (!result) {
          var text = courtyards.textReturn(tasks);
          jQuery('.notice').removeClass('notice-success');
          jQuery('.notice').addClass('notice-error');
          jQuery('.notice > p > strong').text(text['error']);
          jQuery('.notice').show();
          jQuery('.loader').hide();
        } else {
          var success = courtyards.textReturn(tasks);
          jQuery('.notice > p > strong').text(success['success']);
          jQuery('.notice').show();
          jQuery('.loader').hide();
        }
      },
      error: function (result) {
        console.log(result);
        var text = courtyards.textReturn(tasks);
        jQuery('.notice').removeClass('notice-success');
        jQuery('.notice').addClass('notice-error');
        jQuery('.notice > p > strong').text(text['error']);
        jQuery('.notice').show();
        jQuery('.loader').hide();
      },
    });
    return post;
  }

  Courtyards.prototype.textReturn = function (type) {
    switch (type) {
    case 'addPatio':
      var arrayReturn = {
        "success": "Pátio salvo com sucesso !",
        "error": "Erro ao salvar a pátio, tente novamente."
      }
      return arrayReturn;
      break;
    case 'updatePatio':
      var arrayReturn = {
        "success": "Pátio atualizado com sucesso !",
        "error": "Erro ao editar os dados, tente novamente."
      }
      return arrayReturn;
      break;
    case 'deletePatio':
      var arrayReturn = {
        "success": "Pátio excluído com sucesso !",
        "error": "Erro ao excluir a pátio, tente novamente."
      }
      return arrayReturn;
      break;
    }
  }

  Courtyards.prototype.query = function (search, filter) {
    var url = '../wp-content/plugins/transporte/src/courtyards/courtyards.php';
    var task = 'task=query_patios';

    if (typeof (search) === "undefined") {
      return this.send('', url + "?" + task);
    }

    var query = {
      search: search,
      filter: filter
    }
    return this.send(query, url + "?" + task);
  }

  Courtyards.prototype.html_list = function (data) {
    var html = "";

    jQuery.each(data, function (index, value) {
      html += '<tr>';
      html += '<td><input type="checkbox" name="checkbox-actions" class="checkbox-actions" value="' + value.id + '"></td>';
      html += '<td>' + value.title + '</td>';
      html += '<td>' + value.state + '</td>';
      html += '<td>' + value.city + '</td>';
      html += '<td>' + value.cep + '</td>';
      html += '<td style="text-align: center;">' + value.id + '</td>';
      html += '</tr>';
    });
    return html;
  }


  Courtyards.prototype.update = function () {

    var arrayFields = {
      id: jQuery('#id-courtyards').val(),
      title: jQuery('#title-courtyards').val(),
      cep: jQuery('#cep-courtyards').val(),
      street: jQuery('#street-courtyards').val(),
      number: jQuery('#number-courtyards').val(),
      neighborhood: jQuery('#neighborhood-courtyards').val(),
      city: jQuery('#city-courtyards').val(),
      state: jQuery('#state-courtyards').val(),
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
    arrayFields['description'] = jQuery('#description-courtyards').val();
    var url = '../wp-content/plugins/transporte/src/courtyards/courtyards.php';
    var task = 'task=updatePatio';
    return this.send(arrayFields, url + "?" + task);
  }

  Courtyards.prototype.delete = function () {
    var arrayFields = {
      id: jQuery('.checkbox-actions:checked').val(),
    };

    var url = '../wp-content/plugins/transporte/src/courtyards/courtyards.php';
    var task = 'task=deletePatio';
    return this.send(arrayFields, url + "?" + task);
  }
}