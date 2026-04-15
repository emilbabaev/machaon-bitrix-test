<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Подключаем модуль
if (!\Bitrix\Main\Loader::includeModule('local.myvisits')) {
    ShowError('Модуль local.myvisits не установлен');
    return;
}

use MyVisits\VisitsLog\VisitTable;

// Получаем параметры
$recordsPerPage = (int)$this->__arParameters['VIEWS_PER_PAGE'];
if ($recordsPerPage <= 0) {
    $recordsPerPage = 30; // По ТЗ - 30 записей
}

// Выбираем последние N записей
try {
    $rsVisits = VisitTable::getList([
        'select' => ['ID', 'TIMESTAMP_X', 'IP_ADDRESS', 'PAGE_URL', 'REFERER'],
        'order' => ['ID' => 'DESC'],
        'limit' => $recordsPerPage,
    ]);

    $arVisits = [];
    while ($arVisit = $rsVisits->fetch()) {
        $arVisits[] = $arVisit;
    }
} catch (\Exception $e) {
    $arVisits = [];
}

$arResult['ITEMS'] = $arVisits;

$this->IncludeComponentTemplate();
?>
