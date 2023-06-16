<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Context;
use Local\Favorites\Api;
//
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

Loc::loadMessages(__FILE__);

class GetAddFavorites extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = [
            "CACHE_TYPE" => isset($arParams["CACHE_TYPE"])?$arParams["CACHE_TYPE"]:"N",
            "CACHE_TIME" => isset($arParams["CACHE_TIME"])?$arParams["CACHE_TIME"]:0,
            "CACHE_GROUPS" => isset($arParams["CACHE_GROUPS"])?$arParams["CACHE_GROUPS"]:"N",
        ];
        return $result;
    }

    public function setPage404($message)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Connection: close");
        header("Content-Type: application/json");
        header("HTTP/1.0 404 Not Found");
        header('Access-Control-Allow-Origin: *');
        @define("ERROR_404", "Y");
        print json_encode(['error'=>$message], JSON_UNESCAPED_UNICODE);
        return;
    }

    public function setPageJson($arJson, $headerHttp = false)
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Connection: close");
        header("Content-Type: application/json");
        // header('Access-Control-Allow-Origin: *');
        if ($headerHttp) { header($headerHttp); }  // "HTTP/1.0 200 OK"
        print json_encode($arJson, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getHtmlSpecialChars($get) {
        if (is_array($get)) {
            foreach ($get as &$g) {
                if (is_array($g)) {
                    $g = $this->getHtmlSpecialChars($g);
                } else {
                    $g = htmlspecialchars(trim($g));
                }
            }
            return $get;
        } else {
            return htmlspecialchars(trim($get));
        }
    }

    public function post_addFavorites2()
    {
        $this->arResult['id'] = $this->arParams['id'];
        // add
        $res = Api::add($this->arResult['id']);

        if ($res) {
            $this->arResult['title'] = "Успешно добавлено";
        } else {
            $this->arResult['error'] = "Ошибка произошла";
            $this->arResult['title'] = "Ошибка";
        }
    }

    public function post_delFavorites0()
    {
        $this->arResult['id'] = $this->arParams['id'];
        // delete
        $res = Api::dell($this->arResult['id']);

        if ($res) {
            $this->arResult['title'] = "Успешно удален";
        } else {
            $this->arResult['error'] = "Ошибка произошла";
            $this->arResult['title'] = "Ошибка";
        }
    }

    public function executeComponent()
    {
        $request = Context::getCurrent()->getRequest();
        $this->arParams['id'] = ($request->getPost("id")?$this->getHtmlSpecialChars($request->getPost("id")):false);
        $this->arParams['go'] = ($request->getPost("go")?$this->getHtmlSpecialChars($request->getPost("go")):'');

        if(!Loader::includeModule("iblock") || !Loader::includeModule("local.favorites"))
        {
            $this->setPage404('Module not installed');
            return;
        }

        $this->arResult['ITEMS'] = Api::getList();

        // POST methods are named with a prefix "post_"
        $ajax_ = false;
        $method_post = 'post_'.$this->arParams['go'];
        if ($request->getRequestMethod() == "POST" && method_exists($this, $method_post))
        {
            // execute the method
            $this->$method_post();
            $ajax_ = true;
        }

        // for ajax
        if ($ajax_)
            $this->setPageJson($this->arResult);

        //
        $this->includeComponentTemplate();
    }
}