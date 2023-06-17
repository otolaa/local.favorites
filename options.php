<?php
use \Bitrix\Main\Localization\Loc;

loc::loadMessages(__FILE__);

$module_id = "local.favorites";
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
$RIGHT = $APPLICATION->GetGroupRight($module_id);
if($RIGHT >= "R") :

$aTabs = [
    [
        "DIV" => "edit1",
        "TAB" => Loc::getMessage("MAIN_TAB_SET"),
        "ICON" => "perfmon_settings",
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"),
        "OPTIONS" => [
            "Список параметров",
            ["FAVOR_ERROR", "Сообщение об ошибке", null, ["textarea",2,50]],
            "Для системы",
            ["FAVOR_COOKIE_CODE", "Символьный код cookie", null, ["text",50]],
            ["FAVOR_COOKIE_TIME", "Время жизни cookie в днях", null, ["text",50]],
            ['FAVOR_URL_AJAX', 'Путь для AJAX', null, ['text',50]],
        ]
    ],
    [
        "DIV" => "edit2",
        "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
        "ICON" => "perfmon_settings",
        "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS"),
    ],
];

$tabControl = new CAdminTabControl("tabControl", $aTabs);

CModule::IncludeModule($module_id);

if ($_SERVER["REQUEST_METHOD"] == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/perfmon/prolog.php");

    if(strlen($RestoreDefaults)>0)
        COption::RemoveOption("WE_ARE_CLOSED_TEXT_TITLE");
    else
    {
        foreach ($aTabs as $aTab)
        {
            __AdmSettingsSaveOptions($module_id, $aTab['OPTIONS']);
        }
    }

    $Update = $Update.$Apply;
    ob_start();
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
    ob_end_clean();

    LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
} ?>

<h1><?='Настройка'?></h1>
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
    <?
    $tabControl->Begin();
    foreach ($aTabs as $aTab)
    {
        $tabControl->BeginNextTab();
        __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
    }
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
    $tabControl->Buttons(); ?>
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
    <?if(strlen($_REQUEST["back_url_settings"])>0):?>
        <input <?if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
        <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
    <?endif?>
    <input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
    <?=bitrix_sessid_post();?>
    <?$tabControl->End();?>
</form>
<?endif;?>

<? // информационная подсказка и т.д.
echo BeginNote();?>
Время жизни cookie по умолчанию - 1 день = (3600*24*1 секунд) <br>
<? echo EndNote();
