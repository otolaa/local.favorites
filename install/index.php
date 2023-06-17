<?php
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;

loc::loadMessages(__FILE__);

Class local_favorites extends CModule
{
    var $MODULE_ID = "local.favorites";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__.'/version.php');
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("f_module_name");
        $this->MODULE_DESCRIPTION = Loc::getMessage("f_module_desc");
        $this->PARTNER_NAME = 'Alex Noodles';
        $this->PARTNER_URI = '//github.com/otolaa/local.favorites';
    }

    public function getPageLocal($page)
    {
        return str_replace('index.php', $page, Loader::getLocal('modules/'.$this->MODULE_ID.'/install/index.php'));
    }

    public function getStringText($obj)
    {
        return is_array($obj)?implode('<br>', $obj):$obj;
    }

    public function InstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->errors = false;

        // Database tables creation
        $SQL = 'CREATE TABLE IF NOT EXISTS b_favorites
                (
                    ID		INT(11)		NOT NULL auto_increment,
                    TIMESTAMP_X	TIMESTAMP	NOT NULL default current_timestamp on update current_timestamp,
                    ELEM_ID		INT(11) NULL,
                    USER_ID		INT(11) NULL,
                    USER_IP		VARCHAR(255) NULL,
                    PRIMARY KEY (ID)
                ) CHARACTER SET utf8 COLLATE utf8_unicode_ci;';
        $this->errors = $DB->Query($SQL, true);

        if($this->errors !== false) {
            $APPLICATION->ThrowException($this->getStringText($this->errors));
            return false;
        } else {
            return true;
        }
    }

    public function UnInstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->errors = false;

        if (!array_key_exists("save_tables", $arParams) || ($arParams["save_tables"] != "Y")) {
            $this->errors = $DB->Query('DROP TABLE if exists b_favorites', false);
        }

        if($this->errors !== false) {
            $APPLICATION->ThrowException($this->getStringText($this->errors));
            return false;
        }

        return true;
    }

    public function InstallFiles($arParams = [])
    {
        CopyDirFiles($this->getPageLocal('admin'), $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        CopyDirFiles($this->getPageLocal('components'), $_SERVER["DOCUMENT_ROOT"]."/local/components", true, true);
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles($this->getPageLocal('admin'), $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        DeleteDirFiles($this->getPageLocal('components'), $_SERVER["DOCUMENT_ROOT"]."/local/components");
        return true;
    }

    public function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallFiles();
        Option::set($this->MODULE_ID, 'FAVOR_ERROR', 'Произошла ошибка при добавлении в избранное!?');
        Option::set($this->MODULE_ID, 'FAVOR_COOKIE_CODE', 'FAVORITES');
        Option::set($this->MODULE_ID, 'FAVOR_COOKIE_TIME', '1');  // срок действия - 1 день
        $APPLICATION->IncludeAdminFile("Установка модуля ".$this->MODULE_ID, $this->getPageLocal('step.php'));
        return true;
    }

    public function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallFiles();
        Option::delete($this->MODULE_ID); // Will remove all module variables
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля ".$this->MODULE_ID, $this->getPageLocal('unstep.php'));
        return true;
    }
}