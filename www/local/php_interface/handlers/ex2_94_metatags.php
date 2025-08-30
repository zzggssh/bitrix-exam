<?php
use Bitrix\Main\Context;
use Bitrix\Main\Loader;

// Set page props from Metatags iblock based on current URL
$event = function () {
    if (!Loader::includeModule('iblock')) {
        return;
    }
    if (!defined('EX2_META_IBLOCK_ID') || EX2_META_IBLOCK_ID <= 0) {
        return;
    }
    global $APPLICATION;
    $request = Context::getCurrent()->getRequest();
    $path = $request->getRequestedPage();
    if ($path === null || $path === '') {
        return;
    }

    $res = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => EX2_META_IBLOCK_ID, 'ACTIVE' => 'Y', 'NAME' => $path),
        false,
        array('nTopCount' => 1),
        array('ID', 'NAME', 'PROPERTY_title', 'PROPERTY_description')
    );
    if ($item = $res->Fetch()) {
        if (!empty($item['PROPERTY_title_VALUE'])) {
            $APPLICATION->SetPageProperty('title', $item['PROPERTY_title_VALUE']);
        }
        if (!empty($item['PROPERTY_description_VALUE'])) {
            $APPLICATION->SetPageProperty('description', $item['PROPERTY_description_VALUE']);
        }
    }
};

// Run early on every hit
$event();
