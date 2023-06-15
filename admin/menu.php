<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight("local.favorites") > "D") {

    require_once(Loader::getLocal('modules/local.favorites/prolog.php'));

    // the types menu  dev.1c-bitrix.ru/api_help/main/general/admin.section/menu.php
    $aMenu = [
        "parent_menu" => "global_menu_settings", // global_menu_content - раздел "Контент" global_menu_settings - раздел "Настройки"
        "section" => "local.favorites",
        "sort" => 300,
        "module_id" => "local.favorites",
        "text" => 'Избранное модуль',
        "title"=> 'Модуль для добавления id элемента в избранное',
        "icon" => "fileman_menu_icon", // sys_menu_icon bizproc_menu_icon util_menu_icon
        "page_icon" => "fileman_menu_icon", // sys_menu_icon bizproc_menu_icon util_menu_icon
        "items_id" => "menu_favorites",
        "items" => [
            [
                "text" => 'Настройки избранное',
                "title" => 'Настройки избранное',
                "url" => "settings.php?mid=local.favorites&lang=".LANGUAGE_ID,
            ],
            [
                "text" => 'Список избранного',
                "title" => 'Список избранного',
                "url" => "perfmon_table.php?lang=".LANGUAGE_ID."&table_name=b_favorites",
            ],
        ]
    ];

    return $aMenu;
}

return false;