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

        $('.delete-user-ajax-button-js').click(function () {
            let row = $(this).parents('tr');
            deleteUserAjax(row);
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
        let form = $('#delete-user-info-view');
        bootbox.confirm({
            message: form.data('messageText'),
            buttons: {
                confirm: {
                    label: form.data('confirmText'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: form.data('cancelText'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if (result === true) {
                    form.submit();
                }
            }
        });
    }

    /**
     * Lanzamos la confirmacion para eliminar un usuario en la vista index
     * Si se confirma mandamos peticion al controlador y recibimos respuesta
     */
    function deleteUserAjax(row) {
        let userId = row.data('id');
        let form = $('#delete-user-ajax');
        let url = form.attr('action').replace(':USER_ID', userId);
        let data = form.serialize();

        bootbox.confirm({
            message: form.data('messageText'),
            buttons: {
                confirm: {
                    label: form.data('confirmText'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: form.data('cancelText'),
                    className: 'btn-default'
                }
            },
            callback: function (confirmation) {
                if (confirmation === true) {
                    let loadingBar = $('#loading-bar');
                    loadingBar.fadeIn();
                    $.post(url, data, function (result) {
                        loadingBar.fadeOut();
                        let flashMessagesContainer = $('#flash-messages-container');
                        let alertType = '';

                        if (result.removed) {
                            row.fadeOut();
                            $('#users-count').fadeOut(function () {
                                $(this).text(parseInt($(this).text()) - 1).fadeIn();
                            });
                            alertType = 'alert-success';
                        } else {
                            alertType = 'alert-danger';
                        }

                        let htmlFlashMessage = $(
                            '<div class="alert ' + alertType + ' alert-dismissible fade in" role="alert">' +
                            result.message +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                            '</button>' +
                            '</div>'
                        );
                        htmlFlashMessage.hide().appendTo(flashMessagesContainer).fadeIn('slow');
                    }).fail(function () {
                        alert('ERROR');
                    });
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
