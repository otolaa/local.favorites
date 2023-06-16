<?php

namespace Local\Favorites;

use \Bitrix\Main\Engine\CurrentUser;
use \Bitrix\Main\Config\Option;
use \Local\Favorites\FavoritesTable;

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

/**
 * Class Api
 * @package Local\Favorites
 */
class Api
{
    static $MODULE_ID = "local.favorites";

    public static function dell($ID)
    {
        $USER_ID = CurrentUser::get()->getId();

        if ($USER_ID) {

            $result = FavoritesTable::getList([
                'select' => ['ID','ELEM_ID'],
                'filter' => ['=USER_ID'=>$USER_ID, 'ELEM_ID'=>intval($ID)],
            ]);

            while ($row = $result->fetch())
                FavoritesTable::getByPrimary($row['ID'])->fetchObject()->delete();

        } else
            self::addCookie($ID, true);

        return $ID;
    }

    /* add element $ID */
    public static function add($ID)
    {
        $USER_ID = CurrentUser::get()->getId();

        if ($USER_ID) {

            $result = FavoritesTable::add([
                "ELEM_ID" => intval($ID),
                "USER_ID" => intval($USER_ID),
                "USER_IP" => self::getRealUserIp(),
            ]);

            if ($result->isSuccess()) {
                return $result->getId(); // id
            } else return false;

        } else
            return self::addCookie($ID);
    }

    public static function addCookie($ID, $dellCookie = false)
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $f = $request->getCookie("FAVORITES");

        $data = unserialize($f, ["allowed_classes" => false]);

        // add or dell
        if ($dellCookie == true) {
            foreach ($data as $k=>$item) {
                if ($item == intval($ID))
                    unset($data[$k]);
            }
        } else
            $data[] = intval($ID);

        $context = Application::getInstance()->getContext();
        $cookie = new Cookie("FAVORITES", serialize(array_unique($data)), time() + 3600);
        $cookie->setDomain($context->getServer()->getHttpHost());
        $cookie->setHttpOnly(false);
        $cookie->setSecure(false);
        $context->getResponse()->addCookie($cookie);
        $context->getResponse()->flush("");

        return $ID;
    }

    public static function getList()
    {
        $USER_ID = CurrentUser::get()->getId();

        if ($USER_ID) {

            $rows = [];
            $result = FavoritesTable::getList([
                'select' => ['ID','ELEM_ID'],
                'filter' => ['=USER_ID' => $USER_ID],
            ]);

            while ($row = $result->fetch())
                $rows[] = $row['ELEM_ID'];

            return $rows;

        } else {
            $request = Application::getInstance()->getContext()->getRequest();
            $f = $request->getCookie("FAVORITES");

            return unserialize($f, ["allowed_classes" => false]);
        }
    }

    public static function getRealUserIp()
    {
        switch (true) {
            case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
            default : return $_SERVER['REMOTE_ADDR'];
        }
    }
}