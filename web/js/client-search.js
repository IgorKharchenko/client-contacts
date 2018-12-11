$(document).ready(() => {
    // ========= Поиск клиента =========

    /**
     * Скрытие/открытие формы поиска клиента
     */
    $(document).on('click', '#client-search-form-toggle', e => {
        $('.client-search').fadeToggle();
    });

    /**
     * Сброс фильтра поиска на странице клиентов
     */
    $(document).on('click', '.reset-search-filter', e => {
        e.preventDefault();

        let form = $('#search-client-form');
        form.find('input, select').val('');
        form.find('input[type="checkbox"]').prop('checked', false);

        window.location.href = $(e.target).prop('href');

        return true;
    });
});