$(document).ready(() => {
    let loadingSpinner = $('<div class="loader"></div>');

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

        $('#create-client-modal').modal('show');
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

        let clientId = Number($('[name=client-id]').val());

        // create / update
        let mode = $('[name=client-modal-mode]').val();
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
                 * @type {{success: true, data: {photo: String}}} responseData
                 */
                alert
                    .addClass('alert-success')
                    .fadeIn();
                alert
                    .html('create' === mode ? 'Клиент успешно создан.' : 'Клиент успешно сохранён.');

                setTimeout(() => alert.fadeOut(700), 3000);

                if ('create' === mode) {
                    form.find('input').val('');
                }

                $('.client-photo').val('');
                $('.client-image').prop('src', responseData.data.photo);

                $.pjax.defaults.timeout = false;
                $.pjax.reload({container: '#clients-pjax'});
            },
            error:       (error) => {
                alert.addClass('alert-danger').html((JSON.parse(error.responseText)).error);
            },
        });

        return false;
    });

    /**
     * Открытие формы редактирования клиента
     */
    $(document).on('click', '.update-client', async e => {
        let id      = Number($(e.target).data('id'));
        let modal   = $('#create-client-modal');
        let spinner = $('<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');

        let parent = $(e.target).parent();
        let form   = $('.client-form');

        await $.ajax({
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
                for (let attributeName in responseData.data) {
                    // это чтобы IDE лишний раз не ругалась
                    if (!responseData.data.hasOwnProperty(attributeName)) {
                        return false;
                    }

                    let attributeValue = responseData.data[attributeName];

                    if ('photo' === attributeName) {
                        form.find('.client-image').prop('src', attributeValue);
                    } else {
                        form.find(`#client-${attributeName}`).val(attributeValue);
                    }
                }

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