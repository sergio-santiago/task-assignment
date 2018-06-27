let formValidation = {};
formValidation = function () {
    let events = function () {
        $('.select-role-input-js').change(function () {
            isActiveCheckboxPermission();
        });
    };

    function isActiveCheckboxPermission() {
        let option = $('.select-role-input-js').val();
        let checkboxInput = $('.is-active-checkbox-input-js');
        let checkboxLabel = $('.is-active-checkbox-label-js');
        let checkboxHelp = $('.is-active-checkbox-help-js');
        if (option === 'ROLE_ADMIN') {
            checkboxInput.prop('disabled', true);
            checkboxInput.prop('checked', true);
            checkboxLabel.css('color', '#6c747d');
            checkboxLabel.css('cursor', 'not-allowed');
            checkboxHelp.show();
        } else {
            checkboxInput.prop('disabled', false);
            checkboxLabel.css('color', '#333333');
            checkboxLabel.css('cursor', 'pointer');
            checkboxHelp.hide();
        }
    }

    let init = function () {
        isActiveCheckboxPermission();
        events();
    };

    return {
        init: init
    }
}();

$(function () {
    formValidation.init();
});
