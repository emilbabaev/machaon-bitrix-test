<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

$APPLICATION->IncludeComponent(
    'myvisits:logviewer',
    '.default',
    [
        'VIEWS_PER_PAGE' => '30',
        'CACHE_TYPE' => 'N',
        'CACHE_TIME' => 0,
    ],
    false
);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>
