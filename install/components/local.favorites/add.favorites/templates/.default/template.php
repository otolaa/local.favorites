<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use \Bitrix\Main\Config\Option;

$this->setFrameMode(true);
$arJson = $arResult; ?>

<script type="text/javascript">
    BX.ready(function() {
        var arr_list_0 = <?=json_encode($arJson['ITEMS'], JSON_UNESCAPED_UNICODE);?>;
        var arr_list = [];

        //--- object -> array ??!!
        if (
            typeof arr_list_0 === 'object' &&
            !Array.isArray(arr_list_0) &&
            arr_list_0 !== null
        ) {
            for (var key in arr_list_0) {
                arr_list.push(arr_list_0[key]);
            }
        } else {
            arr_list = arr_list_0;
        }

        console.log(arr_list, arr_list.length);

        if (arr_list && arr_list.length > 0) {
            arr_list.forEach(function(item, i) {
                let elm =  document.querySelector(`[data-add-favorites="${item}"]`);
                if (elm) {
                    elm.classList.add('add-favorites-item', 'delete');
                }
            });
        }

        BX.bindDelegate(document.body, 'click', {className: 'add-favorites-item'},
            function(e){
                if(!e) { e = window.event; }

                this.classList.toggle("delete");

                getPostData({
                    id:this.getAttribute('data-add-favorites'),
                    go:this.classList.contains('delete')?'addFavorites2':'delFavorites0',  // check add or dell
                });

                return BX.PreventDefault(e);
            }
        );

        const url_ = `<?=Option::get("local.favorites", "FAVOR_URL_AJAX", '')?>`;
        const getPostData = (data) => {
            BX.ajax({
                url: url_,
                data: data,
                method: 'POST',
                dataType: 'json',
                timeout: 30,
                async: true,
                processData: true,
                scriptsRunFirst: true,
                emulateOnload: true,
                start: true,
                cache: false,
                onsuccess: function(dataJson) {
                    if (dataJson.error) {
                        console.log(dataJson);
                    } else {
                        console.log(dataJson);
                    }
                },
                onfailure: function(e){
                    console.error(e);
                }
            });
        }
    });
</script>
