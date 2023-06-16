<?
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);

use Bitrix\Main\Loader;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

if (!$request->isPost())
    return;

if (!Loader::includeModule('local.favorites') || !Loader::includeModule('iblock'))
    return;

global $APPLICATION;

$APPLICATION->IncludeComponent('local.favorites:add.favorites',".default",
    [
        "CACHE_TYPE" => "N",
        "CACHE_GROUPS" => "N",
    ], false
);

require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");