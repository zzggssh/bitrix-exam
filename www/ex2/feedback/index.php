<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Форма обратной связи");
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:main.feedback",
    "",
    Array(
        "EMAIL_TO" => "admin@example.com",
        "EVENT_NAME" => "FEEDBACK_FORM",
        "OK_TEXT" => "Спасибо, ваше сообщение принято.",
        "REQUIRED_FIELDS" => array("NAME","EMAIL","MESSAGE"),
        "USE_CAPTCHA" => "N"
    ),
    false
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
