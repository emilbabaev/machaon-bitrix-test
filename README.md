# MyVisits для 1C-Bitrix

`MyVisits` — пользовательский модуль для Bitrix, который автоматически сохраняет посещения страниц сайта в таблицу `b_visits_log` и выводит журнал через компонент `myvisits:logviewer`.

Текущий идентификатор модуля: `local.myvisits`.

## Что умеет модуль

- Логирует обычные просмотры страниц на событии `main:OnEpilog`
- Сохраняет дату и время, IP-адрес, URL страницы и referrer
- Создаёт публичную страницу `myvisitors.php` при установке
- Удаляет `myvisitors.php` при деинсталляции
- Показывает последние записи через компонент `myvisits:logviewer`

## Структура проекта

```
local/modules/local.myvisits/
local/components/myvisits/logviewer/
myvisitors.php
```

## Установка

### 1. Разместите файлы

В проекте должны существовать каталоги:

```
local/modules/local.myvisits/
local/components/myvisits/logviewer/
```

### 2. Установите модуль в Bitrix

Откройте:

`http://example.local/bitrix/admin/partner_modules.php?lang=ru`

Дальше:

1. Найдите модуль `local.myvisits`
2. Нажмите `Установить`
3. После установки проверьте, что в корне сайта появился файл `myvisitors.php`

Что делает установка:

- регистрирует модуль `local.myvisits`
- регистрирует обработчик `\MyVisits\VisitLogger::onEpilog`
- копирует публичный файл `myvisitors.php` из `local/modules/local.myvisits/install/files/`

## Как пользоваться

### Вариант 1. Готовая страница

После установки откройте:

`http://example.local/myvisitors.php`

Там уже будет подключён компонент журнала посещений.

### Вариант 2. Вставить компонент на свою страницу

Можно подключить компонент на любой публичной странице:

```php
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->IncludeComponent(
    "myvisits:logviewer",
    ".default",
    [
        "VIEWS_PER_PAGE" => "30",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => 0,
    ],
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
```

### Проверка работы

1. Установите модуль
2. Откройте любую обычную страницу сайта, например `check-ip.php`
3. Перейдите на `myvisitors.php`
4. Убедитесь, что в таблице появилась новая запись

## Что именно логируется

Для каждой записи сохраняются:

- `ID`
- `TIMESTAMP_X`
- `IP_ADDRESS`
- `PAGE_URL`
- `REFERER`

## Что не логируется

Модуль специально пропускает:

- запросы к `/bitrix/`
- запросы к `/upload/`
- запросы к `/ajax/`
- `POST`-запросы
- AJAX-запросы
- обращения к статическим файлам: `css`, `js`, `png`, `jpg`, `svg`, `woff`, `woff2` и т.д.

Это сделано, чтобы журнал не засорялся служебным трафиком.

## Удаление

При удалении модуля Bitrix:

- снимается обработчик `OnEpilog`
- удаляется файл `myvisitors.php`
- снимается регистрация модуля

Если файл `myvisitors.php` остался после старой версии модуля, его можно удалить один раз вручную. В текущей версии удаление уже исправлено.

## Важные замечания


- Идентификатор модуля для `Loader::includeModule()` — `local.myvisits`
- Если после переустановки новые записи не появляются, модуль нужно переустановить, чтобы заново зарегистрировался обработчик `OnEpilog`

## Полезные файлы

- `local/modules/local.myvisits/install/index.php` — установка и удаление модуля
- `local/modules/local.myvisits/lib/VisitLogger.php` — логика записи посещений
- `local/modules/local.myvisits/lib/VisitsLog/VisitTable.php` — ORM-описание таблицы
- `local/components/myvisits/logviewer/component.php` — логика вывода списка
- `myvisitors.php` — готовая публичная страница журнала
