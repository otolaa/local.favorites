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
$this->setFrameMode(true); ?>

<script type="text/javascript">
    BX.ready(function() {
        var arr_list_0 = <?=json_encode($arResult['ITEMS'], JSON_UNESCAPED_UNICODE);?>;
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

                let data_param = {
                    id:this.getAttribute('data-add-favorites'),
                    go:this.classList.contains('delete')?'addFavorites2':'delFavorites0',  // check add or dell
                };

                BX.ajax.runComponentAction('local.favorites:add.favorites',
                    'sendFavorites', { // Вызывается без постфикса Action
                        mode: 'class',
                        data: data_param, // ключи объекта data соответствуют параметрам метода
                    }).then(function(response) {
                        if (response.status === 'success') { // Если форма успешно отправилась
                            console.log(response.data);
                        }
                    });

                return BX.PreventDefault(e);
            }
        );
    });
</script>
