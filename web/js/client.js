$(document).ready(() => {
    let loadingSpinner = $('<div class="loader"></div>');

    // ========= Создание / редактирование клиента =========

    /**
     * Открытие модального окна для создания клиента
     */
    $(document).on('click', '.create-client', () => {
        let form = $('#create-client-form');
        form.find('input').val('');
        form.find('.client-image').prop('src', '');

        // форма в режиме создания клиента
        $('[name=client-modal-mode]').val('create');
        // id редактируемого клиента сбрасываем
        $('[name=client-id]').val(0);

        // оставляем только один контакт
        $.ajax({
            url:        CLIENT_CONTACTS_FORM_URL,
            beforeSend: () => {
                $('.client-contact').remove();
                $('.client-contacts button').remove();
            },
            success:    (responseData) => {
                $('.client-contacts').append(responseData);

                $('.delete-client-contact').hide();

                $('#create-client-modal').modal('show');
            }
        });
    });

    /**
     * Отправка формы создания клиента
     */
    $(document).on('beforeSubmit', '#create-client-form', e => {
        e.preventDefault();

        let form  = $(e.target);
        let alert = $('.create-client-alert');
        alert
            .addClass('alert')
            .removeClass('alert-success')
            .removeClass('alert-danger');

        let clientId             = Number($('[name=client-id]').val());
        let redirectToClientPage = Number($('[name=redirectToClientPage]').val());
        let isModal              = Number($('[name=is-modal]').val());

        // create / update
        let mode = $('[name=client-modal-mode]').val();
        if (0 === isModal && 1 === redirectToClientPage) {
            mode = 'update';
        }

        // в случае с созданием клиента если хотя бы один его контакт заполнен,
        // то форма также считается валидной
        if (!_validateClientContacts()) {
            return false;
        }

        let removedContactsIds = $('[name="removedContactsIds"]')
            .val()
            .split(',')
            .filter(contact => contact.length > 0);

        // сначала создаём клиента, после чего заполняем для него контакты
        $.ajax({
            url:         'create' === mode ? CREATE_CLIENT_URL : UPDATE_CLIENT_URL + `&id=${clientId}`,
            method:      'POST',
            data:        new FormData($(e.target)[0]),
            processData: false,
            contentType: false,
            beforeSend:  () => {
                alert.html(loadingSpinner.clone());
            },
            success:     (responseData) => {
                /**
                 * @type {{success: true, data: {
                 *      id: Number, photo: String,
                 *      короче в data перечислены все поля из таблицы client
                 * }}} responseData
                 */
                $.ajax({
                    url:     SAVE_CONTACT_URL,
                    method:  'POST',
                    data:    {
                        contacts:           _getContactsFromClientForm(responseData.data.id),
                        clientId:           responseData.data.id,
                        removedContactsIds: removedContactsIds,
                    },
                    success: (resp) => {
                        alert
                            .addClass('alert-success')
                            .fadeIn();
                        alert
                            .html('create' === mode ? 'Клиент успешно создан.' : 'Клиент успешно сохранён.');

                        setTimeout(() => alert.fadeOut(700), 3000);

                        if ('create' === mode) {
                            form.find('input').val('');
                        }

                        $('#client-photo').val('');
                        $('.client-image').prop('src', responseData.data.photo);

                        if (1 === redirectToClientPage) {
                            setTimeout(() => {
                                window.location.href = VIEW_CLIENT_URL + '&id=' + clientId;
                            }, 3000);
                        } else {
                            $.pjax.defaults.timeout = false;
                            $.pjax.reload({container: '#clients-pjax'});
                        }
                    },
                    error:   (error) => {
                        alert.addClass('alert-danger').html((JSON.parse(error.responseText)).error).fadeIn();
                    },
                });
            },
            error:       (error) => {
                alert.addClass('alert-danger').html((JSON.parse(error.responseText)).error).fadeIn();
            },
        });

        return false;
    });

    /**
     * Открытие формы редактирования клиента
     */
    $(document).on('click', '.update-client', e => {
        let id      = Number($(e.target).data('id'));
        let modal   = $('#create-client-modal');
        let spinner = $('<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');

        let parent = $(e.target).parent();
        let form   = $('.client-form');

        $.ajax({
            url:        GET_CLIENT_URL + '&clientId=' + id,
            method:     'GET',
            beforeSend: () => {
                // показываем spinner
                parent.find('a, button').hide();
                parent.append(spinner.clone());
                $(e.target).prop('disabled', true);
            },
            success:    (responseData) => {
                /**
                 * @type {{success: Boolean, data: {}}} responseData
                 */
                _setClientForm(form, responseData.data);

                // показываем форму контактов
                _setClientContactsForm(id);

                // форма в режиме редактирования клиента
                $('[name=client-modal-mode]').val('update');
                // задаём id редактируемого клиента
                $('[name=client-id]').val(responseData.data.id);

                modal.modal('show');
            },
            complete:   () => {
                // скрываем spinner
                parent.find('a, button').show();
                parent.find('.lds-roller').remove();
                $(e.target).prop('disabled', false);
            },
        });

        return false;
    });
});

/**
 * Устанавливает форму контактов клиента.
 *
 * @param {Number|Null} clientId - id клиента.
 *
 * @private
 */
const _setClientContactsForm = (clientId = null) => {
    let url = CLIENT_CONTACTS_FORM_URL;
    if (null !== url) {
        url += '&clientId=' + clientId;
    }

    $.ajax({
        url:     url,
        method:  'GET',
        success: (form) => {
            $('.client-contacts-wrap').show();
            let clientContacts = $('.client-contacts');
            clientContacts.html($(form).clone());

            let removeContactButton = clientContacts.find('.delete-client-contact');
            if (1 === removeContactButton.length) {
                removeContactButton.hide();
            }
        },
        error:   () => {
        },
    });
};

/**
 * Устанавливает значения в форме клиента.
 *
 * @param {jQuery|HTMLElement} form - форма.
 * @param {Object}             data - данные для формы.
 *
 * @return {boolean}
 *
 * @private
 */
const _setClientForm = (form, data) => {
    for (let attributeName in data) {
        // это чтобы IDE лишний раз не ругалась
        if (!data.hasOwnProperty(attributeName)) {
            return false;
        }

        let attributeValue = data[attributeName];

        if ('photo' === attributeName) {
            form.find('.client-image').prop('src', attributeValue);
        } else {
            form.find(`#client-${attributeName}`).val(attributeValue);
        }
    }
};

/**
 * Возвращает контакты с формы клиента.
 *
 * @param {Number|Null} clientId - id клиента либо null если клиент ещё не создан.
 *
 * @return {Array}
 *
 * @private
 */
const _getContactsFromClientForm = (clientId = null) => {
    let contacts = [];

    $('.client-contact').each((index, contact) => {
        contacts.push({
            id:           Number($(contact).find('[name="ClientContact[id]"]').val()) || null,
            client_id:    clientId,
            contact_type: $(contact).find('[name="ClientContact[contact_type]"]').val(),
            content:      $(contact).find('[name="ClientContact[content]"]').val(),
        });
    });

    return contacts;
};

/**
 * Валидирует форму контактов клиента.
 * @todo переписать этот бред с использованием jquery.validation плагина {@link https://jqueryvalidation.org/}
 * З.Ы. я пытался использовать $.yiiActiveForm('validate'),
 * но он почему-то не заработал с несколькими одинаковыми ActiveForm-ами:
 * скорее всего из-за того, что в формах есть элементы с гвоздями прибитыми id-шниками
 * типа "#clientcontact-content"
 *
 * @return {Boolean}
 *
 * @private
 */
const _validateClientContacts = () => {
    let contacts = $('.add-contact-form');
    let valid    = true;

    contacts.each((index, contact) => {
        let type    = $(contact).find('[name="ClientContact[contact_type]"]');
        let content = $(contact).find('[name="ClientContact[content]"]');

        if ('' === type.val()) {
            $(type)
                .parent()
                .addClass('has-error')
                .find('.help-block')
                .html('Пожалуйста, выберите тип.');
            valid = false;
        }
        if ('' === content.val()) {
            $(content)
                .parent()
                .addClass('has-error')
                .find('.help-block')
                .html('Пожалуйста, укажите контакт.');
            valid = false;
        }
        if (content.val().length > 30) {
            $(content)
                .parent()
                .addClass('has-error')
                .find('.help-block')
                .html('Контакт не может содержать более 30 символов.');
            valid = false;
        }

        $(document).on('keyup paste change', '[name="ClientContact[contact_type]"], [name="ClientContact[content]"]', e => {
            $(e.target)
                .parent()
                .removeClass('has-error')
                .find('.help-block')
                .html('');
        });
    });

    return valid;
};
