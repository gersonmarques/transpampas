jQuery(document).ready(function (jQuery) {
  request = new RequestTransport();

  jQuery(document).on('click', '#input_save', function () {
    jQuery('.loader').css('display', 'inline-block');
    setTimeout(function () {
      request.save();
    }, 1000);
  });

  jQuery(document).on('click', '.checkbox-actions', function () {
    if (jQuery('.checkbox-actions:checked').length > 1) {
      jQuery('.checkbox-actions').prop('checked', false);
      jQuery(this).prop('checked', true);
    }

    if (jQuery('.checkbox-actions:checked').length == 1) {
      jQuery('#input_change').prop('disabled', false);
      jQuery('#input_delete').prop('disabled', false);
    }

    if (jQuery('.checkbox-actions:checked').length < 1) {
      jQuery('#input_change').prop('disabled', true);
      jQuery('#input_delete').prop('disabled', true);
    }
  });

  jQuery(document).on('click', '#input_change', function () {
    location.href = "?page=list_requests&id=" + jQuery('.checkbox-actions:checked').val();
  });

  jQuery(document).on('click', '#input_update', function () {
    jQuery('.loader').css('display', 'inline-block');
    jQuery('.loader').css('margin-top', '4px');
    setTimeout(function () {
      request.update();
    }, 1000);
  });

  jQuery(document).on('click', '#input_delete', function () {
    jQuery('.loader').css('display', 'inline-block');
    jQuery('.loader').css('margin-top', '4px');
    setTimeout(function () {
      request.delete();
    }, 1000);
  });

  jQuery(document).on('click', '#search-request-transport', function () {
    jQuery('.loader-search').css('display', 'inline-block');
    setTimeout(function () {
      var search = jQuery('#query-request-transport').val();
      var filter = jQuery('#param-request-transport').val();
      request.query(search, filter);
    }, 1000);
  });

  jQuery(document).on('click', '.rg_cnh_label', function () {
    jQuery('.rg_cnh').click()
  });

  jQuery(document).on('click', '.crlv_label', function () {
    jQuery('.crlv').click()
  });

  jQuery(document).on('change', '.rg_cnh', function () {
    if (!this.files[0]) {
      jQuery(this).text('Cópia/Foto legível de RG OU CNH (.pdf .jpg .png)')
    }
    jQuery('.rg_cnh_label').text(this.files[0].name)
  });

  jQuery(document).on('change', '.crlv', function () {
    if (!this.files[0]) {
      jQuery(this).text('Cópia/Foto legível de CRLV (.pdf .jpg .png)')
    }
    jQuery('.crlv_label').text(this.files[0].name)
  });
});