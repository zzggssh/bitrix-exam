<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="simplecomp">
<?php foreach ($arResult['CLASSIFIERS'] as $classifier): ?>
    <div class="classifier">
        <h3><?=htmlspecialcharsbx($classifier['NAME'])?></h3>
        <?php if (!empty($classifier['DATE_ACTIVE_FROM'])): ?>
            <div class="date">Дата активности: <?=htmlspecialcharsbx($classifier['DATE_ACTIVE_FROM'])?></div>
        <?php endif; ?>
        <?php if (!empty($classifier['ITEMS'])): ?>
            <ul class="items">
                <?php foreach ($classifier['ITEMS'] as $item): ?>
                    <li>
                        <?php
                        $url = '';
                        if (!empty($arResult['DETAIL_URL_TEMPLATE'])) {
                            $url = str_replace(
                                ['#SECTION_ID#', '#ELEMENT_CODE#'],
                                [(string)$item['SECTION_ID'], (string)$item['CODE']],
                                $arResult['DETAIL_URL_TEMPLATE']
                            );
                        }
                        ?>
                        <strong><?=htmlspecialcharsbx($item['NAME'])?></strong>
                        — Цена: <?=htmlspecialcharsbx($item['PRICE'])?>
                        — Материал: <?=htmlspecialcharsbx($item['MATERIAL'])?>
                        — Арт.: <?=htmlspecialcharsbx($item['ARTNUMBER'])?>
                        <?php if ($url): ?>
                            — <a href="<?=htmlspecialcharsbx($url)?>">детально</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div>Нет товаров.</div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
