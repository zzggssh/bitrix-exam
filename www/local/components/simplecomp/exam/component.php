<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class SimpleCompExamComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        $params['CATALOG_IBLOCK_ID'] = (int)($params['CATALOG_IBLOCK_ID'] ?? 0);
        $params['CLASSIFIER_IBLOCK_ID'] = (int)($params['CLASSIFIER_IBLOCK_ID'] ?? 0);
        $params['ELEMENT_PROP_CODE'] = trim((string)($params['ELEMENT_PROP_CODE'] ?? ''));
        $params['DETAIL_URL_TEMPLATE'] = trim((string)($params['DETAIL_URL_TEMPLATE'] ?? ''));
        if (!isset($params['CACHE_TIME'])) {
            $params['CACHE_TIME'] = 36000000;
        }
        return $params;
    }

    private function abortIfInvalidParams(): bool
    {
        if ($this->arParams['CATALOG_IBLOCK_ID'] <= 0 || $this->arParams['CLASSIFIER_IBLOCK_ID'] <= 0 || $this->arParams['ELEMENT_PROP_CODE'] === '') {
            ShowError('Некорректные параметры компонента');
            return true;
        }
        if (!Loader::includeModule('iblock')) {
            ShowError('Модуль iblock не установлен');
            return true;
        }
        return false;
    }

    public function executeComponent()
    {
        global $APPLICATION;
        if ($this->abortIfInvalidParams()) {
            return;
        }

        if ($this->StartResultCache(false)) {
            $this->arResult = [
                'CLASSIFIERS' => [],
            ];

            // 1) Получаем классификатор (элементы)
            $classifierIds = [];
            $resClass = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => $this->arParams['CLASSIFIER_IBLOCK_ID'],
                    'ACTIVE' => 'Y',
                    'ACTIVE_DATE' => 'Y',
                    'CHECK_PERMISSIONS' => 'Y',
                ],
                false,
                false,
                ['ID','NAME','DATE_ACTIVE_FROM']
            );
            while ($row = $resClass->Fetch()) {
                $this->arResult['CLASSIFIERS'][$row['ID']] = [
                    'ID' => (int)$row['ID'],
                    'NAME' => $row['NAME'],
                    'DATE_ACTIVE_FROM' => $row['DATE_ACTIVE_FROM'],
                    'ITEMS' => [],
                ];
                $classifierIds[] = (int)$row['ID'];
            }

            // 2) Получаем товары, где свойство привязки содержит любой из классификаторов
            if (!empty($classifierIds)) {
                $propCode = $this->arParams['ELEMENT_PROP_CODE'];
                $resItems = CIBlockElement::GetList(
                    [],
                    [
                        'IBLOCK_ID' => $this->arParams['CATALOG_IBLOCK_ID'],
                        'ACTIVE' => 'Y',
                        'CHECK_PERMISSIONS' => 'Y',
                        'PROPERTY_'.$propCode => $classifierIds,
                    ],
                    false,
                    false,
                    ['ID','NAME','CODE','IBLOCK_SECTION_ID','PROPERTY_'.$propCode,'PROPERTY_MATERIAL','PROPERTY_ARTNUMBER','PROPERTY_PRICE']
                );
                $itemsTotal = 0;
                while ($row = $resItems->Fetch()) {
                    $linked = $row['PROPERTY_'.$propCode.'_VALUE'];
                    if (is_array($linked)) {
                        foreach ($linked as $cid) {
                            $cid = (int)$cid;
                            if (isset($this->arResult['CLASSIFIERS'][$cid])) {
                                $itemsTotal++;
                                $this->arResult['CLASSIFIERS'][$cid]['ITEMS'][] = [
                                    'ID' => (int)$row['ID'],
                                    'NAME' => $row['NAME'],
                                    'CODE' => $row['CODE'],
                                    'SECTION_ID' => (int)$row['IBLOCK_SECTION_ID'],
                                    'MATERIAL' => $row['PROPERTY_MATERIAL_VALUE'],
                                    'ARTNUMBER' => $row['PROPERTY_ARTNUMBER_VALUE'],
                                    'PRICE' => $row['PROPERTY_PRICE_VALUE'],
                                ];
                            }
                        }
                    } elseif ($linked) {
                        $cid = (int)$linked;
                        if (isset($this->arResult['CLASSIFIERS'][$cid])) {
                            $itemsTotal++;
                            $this->arResult['CLASSIFIERS'][$cid]['ITEMS'][] = [
                                'ID' => (int)$row['ID'],
                                'NAME' => $row['NAME'],
                                'CODE' => $row['CODE'],
                                'SECTION_ID' => (int)$row['IBLOCK_SECTION_ID'],
                                'MATERIAL' => $row['PROPERTY_MATERIAL_VALUE'],
                                'ARTNUMBER' => $row['PROPERTY_ARTNUMBER_VALUE'],
                                'PRICE' => $row['PROPERTY_PRICE_VALUE'],
                            ];
                        }
                    }
                }
                $this->arResult['TOTAL_ITEMS'] = $itemsTotal;
            } else {
                $this->arResult['TOTAL_ITEMS'] = 0;
            }

            $this->arResult['DETAIL_URL_TEMPLATE'] = $this->arParams['DETAIL_URL_TEMPLATE'];

            $this->IncludeComponentTemplate();
        }

        $total = (int)($this->arResult['TOTAL_ITEMS'] ?? 0);
        $APPLICATION->SetTitle('Разделов: '.count($this->arResult['CLASSIFIERS']));
    }
}

$component = new SimpleCompExamComponent($this);
$component->executeComponent();
