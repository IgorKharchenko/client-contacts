# Client contacts database

## Зависимости
- php >= 7.1
- mysql >= 5.7

## Установка
- Создайте БД
- В файле `config/db.php` в секции `dsn` поменяйте название БД
- Накатите миграции:
```bash
yii migrate
```

После чего приложение будет доступно по адресу `http://client-contacts.local/`.

## Страницы
- `/clients` - список клиентов
- `/contacts` - список контактов клиентов