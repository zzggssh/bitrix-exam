<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    'GROUPS' => array(),
    'PARAMETERS' => array(
        'CATALOG_IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'ID инфоблока с каталогом',
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ),
        'CLASSIFIER_IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'ID инфоблока с классификатором',
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ),
        'ELEMENT_PROP_CODE' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Код свойства привязки товара к классификатору',
            'TYPE' => 'STRING',
            'DEFAULT' => 'FIRMS',
        ),
        'DETAIL_URL_TEMPLATE' => array(
            'PARENT' => 'ADDITIONAL_SETTINGS',
            'NAME' => 'Шаблон ссылки на детальный просмотр',
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ),
        'CACHE_TIME'  => array('DEFAULT' => 36000000),
        'CACHE_TYPE' => array('DEFAULT' => 'A'),
    ),
);
