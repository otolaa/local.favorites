<?php

\Bitrix\Main\Loader::registerAutoLoadClasses("local.favorites", [
    '\Local\Favorites\Api'=>'lib/api.php',
    '\Local\Favorites\FavoritesTable'=>'lib/favoritestable.php',
]);