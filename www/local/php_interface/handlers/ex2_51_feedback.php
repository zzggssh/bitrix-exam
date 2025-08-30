<?php
use Bitrix\Main\EventManager;

// Register handler
EventManager::getInstance()->addEventHandler('main', 'OnBeforeEventAdd', function (&$event, &$lid, &$arFields, &$messageId, &$files, &$languageId) {
    if ($event !== 'FEEDBACK_FORM') {
        return true;
    }

    global $USER;
    $formName = isset($arFields['AUTHOR']) ? trim((string)$arFields['AUTHOR']) : '';

    if (is_object($USER) && $USER->IsAuthorized()) {
        $userId = (int)$USER->GetID();
        $userLogin = (string)$USER->GetLogin();
        $userName = trim((string)$USER->GetFullName());
        if ($userName === '') {
            $userName = (string)$USER->GetFirstName();
        }
        $author = sprintf('Пользователь авторизован: %d (%s) %s, данные из формы: %s', $userId, $userLogin, $userName, $formName);
    } else {
        $author = sprintf('Пользователь не авторизован, данные из формы: %s', $formName);
    }

    $arFields['AUTHOR'] = $author;

    if (class_exists('CEventLog')) {
        CEventLog::Add([
            'SEVERITY' => 'INFO',
            'AUDIT_TYPE_ID' => 'EX2_51_REPLACE',
            'MODULE_ID' => 'main',
            'ITEM_ID' => 0,
            'DESCRIPTION' => 'Замена данных в отсылаемом письме – ' . $author,
        ]);
    }

    return true;
});
