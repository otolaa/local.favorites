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
```
Array
(
    [0] => 2
)
```

DataManager для элементов в базе данных, таблица b_favorites:
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
