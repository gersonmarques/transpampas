/**
 * Created by brunoreis on 08/05/2017.
 */

var Validation = function () {

  /**
   * Return a boolean about the fields fill
   * @param Array
   * @return Boolean
   */
  Validation.prototype.fields = function (arrayFields) {
    var result;
    var validation = new Validation();

    if (typeof arrayFields != 'object') {
      console.log(typeof arrayFields);
      return false;
    }

    jQuery.each(arrayFields, function (index, value) {
      result = true;
      if (value === undefined)
        result = false;

      if (value.trim() === null)
        result = false;

      if (value.trim() === "")
        result = false;

      if (!result) {
        if (!validation.border(index, '#d43f3a')) {
          return false;
        }
      } else {
        validation.border(index, '#ddd');
      }

    });
    return result;
  }

  /**
   * Return array with elements different
   * @param fieldOld
   * @param fieldNew
   * @constructor
   */
  Validation.prototype.arrayEquals = function (fieldOld, fieldNew) {
    var arrayReturn = {};

    jQuery.each(fieldOld, function (index, value) {

      if (fieldOld[index] != fieldNew[index])
        arrayReturn[index] = fieldNew[index];
    });
    return arrayReturn;
  }

  Validation.prototype.border = function (index, border) {
    var result;
    switch (index) {
    case 'title':
      jQuery("#title_events_calendar").css('border', '1px solid ' + border);
      result = false;
      break;
    case 'description':
      jQuery("#description_text").css('border', '1px solid ' + border);
      result = false;
      break;
    case 'date_initial':
      jQuery("#date_initial").css('border', '1px solid ' + border + '');
      result = false;
      break;
    case 'date_final':
      jQuery("#date_final").css('border', '1px solid ' + border);
      result = false;
      break;
    default:
      result = true;
      break;
    }
    return result;
  }

}