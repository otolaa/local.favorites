# Избранное (Модуль для 1С-Битрикс)

Модуль для добавления избранных ID элементов.
Если пользователь не авторизован, использует Cookie.

```php
use Local\Favorites\Api;

// add
$elem_id = 2;
$res = Api::add($elem_id);

// delete
$res = Api::dell(3);

// get list 
$items = Api::getList();

return $items;
```

ORM DataManager для элементов в базе данных, таблица b_favorites:
```php
use Local\Favorites\FavoritesTable;

$rows = [];
$result = FavoritesTable::getList([
    'select' => ['ID','ELEM_ID'],
    'filter' => ['=USER_ID' => $USER_ID],
]);

while ($row = $result->fetch())
    $rows[] = $row['ELEM_ID'];

return $rows;
```


## Установка модуля

### Composer
```bash
composer require otolaa/local.favorites
```

### Ручная установка
* Создать папку `local.favorites` в папке `/local/modules/` или `/bitrix/modules/`
* Скопировать файлы модуля в папку `local.favorites`

## Требования
* PHP >= 8.0

## Внедрение, использование модуля
* добавить компонент `add.favorites` в шапку `header.php` в шаблон сайта
```php
<? $APPLICATION->IncludeComponent('local.favorites:add.favorites',".default",
    [
        "CACHE_TYPE" => "N",
        "CACHE_GROUPS" => "N",
    ], false
); ?>
```

* добавить в шаблон карточки товара `catalog.item` ссылку-кнопку для добавление в избранное
```
<a href="javascript:void(0);" data-add-favorites="<?=$item['ID']?>" class="add-favorites-item">
    <span>&#10084;</span>
</a>
```
![button](https://github.com/otolaa/local.favorites/blob/main/install/img/button.png "button")


* изменить или добавить настройки в модуле
![local.favorites](https://github.com/otolaa/local.favorites/blob/main/install/img/setting.png "local.favorites")

