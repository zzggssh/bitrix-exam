<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?>
<?$APPLICATION->IncludeComponent(
    "simplecomp:exam",
    ".default",
    array(
        "CATALOG_IBLOCK_ID" => "2",
        "CLASSIFIER_IBLOCK_ID" => "3",
        "ELEMENT_PROP_CODE" => "FIRMS",
        "DETAIL_URL_TEMPLATE" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => 36000000
    ),
    false
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
