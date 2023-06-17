<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Local\Favorites\Api;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

//
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

Loc::loadMessages(__FILE__);

class GetAddFavorites extends \CBitrixComponent implements Controllerable
{
    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'sendFavorites' => [
                'prefilters' => [
                    //new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    /**
     * @param integer $id
     * @param string $go
     * @return array
     */
    public function sendFavoritesAction($id, $go)
    {
        $arJson = ['id' => $id, 'go' => $go];
        if (Loader::includeModule("local.favorites") && $id && $go)
        {
            if ($go == 'addFavorites2') {
                $res = Api::add($id);
                $arJson['add_element_id'] = $res?$id:false;
            }

            if ($go == 'delFavorites0')
                $arJson['dell_element_id'] = Api::dell($id);
        }

        //AddMessage2Log("\n".var_export($arJson, true). " \n \r\n ", "arJson");
        return $arJson;
    }

    public function onPrepareComponentParams($arParams)
    {
        $result = [
            "CACHE_TYPE" => isset($arParams["CACHE_TYPE"])?$arParams["CACHE_TYPE"]:"N",
            "CACHE_TIME" => isset($arParams["CACHE_TIME"])?$arParams["CACHE_TIME"]:0,
            "CACHE_GROUPS" => isset($arParams["CACHE_GROUPS"])?$arParams["CACHE_GROUPS"]:"N",
        ];

        return $result;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule("iblock") || !Loader::includeModule("local.favorites"))
            return;

        $this->arResult['ITEMS'] = Api::getList();

        //
        $this->includeComponentTemplate();
    }
}