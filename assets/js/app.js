const $ = require('jquery');
require('bootstrap-sass');
let bootbox = require('bootbox');

/**
 * CONFIRMATION MENU
 * Boton dentro de formulario con =>
 * clase -> launch-confimation-js
 * atributos -> data-message-text data-confirm-text data-confirm-button-style data-cancel-text data-cancel-button-style
 */
$('.launch-confimation-js').click(function () {
    bootbox.confirm({
        message: $(this).data().messageText,
        buttons: {
            confirm: {
                label: $(this).data().confirmText,
                className: 'btn-' + $(this).data().confirmButtonStyle
            },
            cancel: {
                label: $(this).data().cancelText,
                className: 'btn-' + $(this).data().cancelButtonStyle
            }
        },
        callback: function (result) {
            if (result === true) {
                $('.launch-confimation-js').closest('form').submit();
            }
        }
    });
});
