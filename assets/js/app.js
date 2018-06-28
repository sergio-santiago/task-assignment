const $ = require('jquery');
require('bootstrap-sass');
let bootbox = require('bootbox');

let app = {};
app = function () {
    let events = function () {
        $('.select-role-input-js').change(function () {
            isActiveCheckboxPermission();
        });

        $('.delete-launch-confimation-info-js').click(function () {
            deleteLaunchConfimationInfo();
        });
    };

    /**
     * Bloqueo checkbox en edit y add user
     */
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

    /**
     * Confirmacion eliminacion usuario en vista info
     */
    function deleteLaunchConfimationInfo() {
        let button = $('.delete-launch-confimation-info-js');
        bootbox.confirm({
            message: button.data('messageText'),
            buttons: {
                confirm: {
                    label: button.data('confirmText'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: button.data('cancelText'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if (result === true) {
                    button.closest('form').submit();//SI SE PONE ID A FORMULARIO CAMBIAR...
                }
            }
        });
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
    app.init();
});
