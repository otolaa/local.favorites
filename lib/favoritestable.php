<?php
namespace Local\Favorites;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);

/**
 * Class FavoritesTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> TIMESTAMP_X datetime optional default current datetime
 * <li> ELEM_ID int optional
 * <li> USER_ID int optional
 * <li> USER_IP string(255) optional
 * </ul>
 *
 * @package Local\Favorites
 **/

class FavoritesTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_favorites';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('FAVORITES_ENTITY_ID_FIELD')
                ]
            ),
            new DatetimeField(
                'TIMESTAMP_X',
                [
                    'default' => function()
                    {
                        return new DateTime();
                    },
                    'title' => Loc::getMessage('FAVORITES_ENTITY_TIMESTAMP_X_FIELD')
                ]
            ),
            new IntegerField(
                'ELEM_ID',
                [
                    'title' => Loc::getMessage('FAVORITES_ENTITY_ELEM_ID_FIELD')
                ]
            ),
            new IntegerField(
                'USER_ID',
                [
                    'title' => Loc::getMessage('FAVORITES_ENTITY_USER_ID_FIELD')
                ]
            ),
            new StringField(
                'USER_IP',
                [
                    'validation' => [__CLASS__, 'validateUserIp'],
                    'title' => Loc::getMessage('FAVORITES_ENTITY_USER_IP_FIELD')
                ]
            ),
        ];
    }

    /**
     * Returns validators for USER_IP field.
     *
     * @return array
     */
    public static function validateUserIp()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }
}