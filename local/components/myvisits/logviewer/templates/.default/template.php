<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if(!empty($arResult['ITEMS'])):?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата и время</th>
                <th>IP-адрес</th>
                <th>Страница</th>
                <th>Реферер</th>
            </tr>
        </thead>
        <tbody>
            <?foreach($arResult['ITEMS'] as $arVisit):?>
                <tr>
                    <td><?=$arVisit['ID']?></td>
                    <td><?=$arVisit['TIMESTAMP_X']->format('d.m.Y H:i:s')?></td>
                    <td><?=htmlspecialcharsbx($arVisit['IP_ADDRESS'])?></td>
                    <td>
                        <a href="<?=htmlspecialcharsbx($arVisit['PAGE_URL'])?>" target="_blank">
                            <?=htmlspecialcharsbx($arVisit['PAGE_URL'])?>
                        </a>
                    </td>
                    <td>
                        <?if(!empty($arVisit['REFERER'])):?>
                            <a href="<?=htmlspecialcharsbx($arVisit['REFERER'])?>" target="_blank">
                                <?=htmlspecialcharsbx($arVisit['REFERER'])?>
                            </a>
                        <?else:?>
                            Прямой визит
                        <?endif;?>
                    </td>
                </tr>
            <?endforeach;?>
        </tbody>
    </table>
<?else:?>
    <p>Записи в журнале не найдены.</p>
<?endif;?>
