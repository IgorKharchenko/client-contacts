$(document).ready(() => {
    // ========= Контакты =========

    /**
     * Добавление нового контакта
     */
    $(document).on('click', '.add-client-contact', e => {
        let contacts = $('.client-contact');

        let contact = contacts.eq(0).clone();
        contact.find('input, select').val('');
        contact.find('.form-group').removeClass('has-error');
        contact.find('.help-block').html('');

        // $.data() с клонированными элементами почему-то не захотел работать
        contact.attr('data-key', contacts.length + 1);
        contact.find('.delete-client-contact').attr('data-key', contacts.length + 1);

        // показываем кнопки удаления контакта
        $('.client-contacts')
            .append(contact)
            .find('.delete-client-contact')
            .fadeIn();
    });

    /**
     * Удаление контакта
     */
    $(document).on('click', '.delete-client-contact', e => {
        e.preventDefault();

        let formKey     = Number($(e.target).data('key'));
        let contactForm = $(`.client-contact[data-key=${formKey}]`);
        contactForm.fadeOut(500);
        setTimeout(() => contactForm.remove(), 500);

        // как минимум один контакт быть обязан
        let contacts = $('.client-contact');
        if (contacts.length <= 2) {
            // скрываем у последнего оставшегося контакта кнопку удаления
            contacts.first().find('.delete-client-contact').fadeOut(500);
        }

        // если контакт имеет id, то помечаем его как удалённый,
        // чтобы удалить его на бэке
        let contactId = Number(contactForm.find('[name="ClientContact[id]"]').val());
        if (contactId) {
            let removed = $('[name="removedContactsIds"]');
            removed.val(_markClientContactRemoved(removed.val(), contactId));
        }

        return false;
    });
});

/**
 * Добавляет id контакта клиента в список удалённых контактов,
 * тем самым помечая его удалённым.
 *
 * @param {Array.<Number|String>|String} contactIds - массив чисел или строка.
 * @param {Number|String}                contactId  - id контакта.
 *
 * @returns {Array.<Number|String>}
 */
const _markClientContactRemoved = function (contactIds, contactId) {
    if ('' === contactIds) {
        contactIds = [];
    }
    // Переводим строку типа "23,24,29" в массив, если передана такая строка.
    if (Array.isArray(contactIds)) {
        contactIds = contactIds.toString();
    }
    contactIds = contactIds.split(',');
    contactIds.push(contactId);
    return contactIds;
};