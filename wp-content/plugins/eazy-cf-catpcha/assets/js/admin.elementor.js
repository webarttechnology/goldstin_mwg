function init($) {
  if(typeof elementor === 'undefined') {
    return;
  }

  var renderField = function renderField(inputField, item, i, settings) {
    var
      itemClasses = item.css_classes,
      required = '',
      placeholder = '',
      label = '',
      checked = '';

    required = 'required';

    if (item.placeholder) {
      placeholder = ' placeholder="' + item.placeholder + '"';
    }

    itemClasses = 'elementor-field-textual ' + itemClasses;

    return '<span class="elementor-field-group eazycfc_captcha-exercise">\
      <label for="form_field_' + i + '">\
        <span>10</span>\
        <span>+</span>\
        <span>5</span> =\
      </label>\
      <input size="1" type="text"' + placeholder + ' class="elementor-field-textual elementor-time-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >\
    </span>';
  }

  elementor.hooks.addFilter('elementor_pro/forms/content_template/field/eazycfcaptcha', renderField, 10, 4);
}

jQuery(init)
