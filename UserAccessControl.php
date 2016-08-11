<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 8/27/14
 * Time: 14:54
 */

namespace rocketfirm\engine;


class UserAccessControl
{

    public static function checkUserRole()
    {
        if (\Yii::$app->user->getIdentity()) {
            $role = \Yii::$app->user->getIdentity()->role;

            if (in_array($role, ['admin', 'editor', 'author'])) {
                return true;
            }
        }

        return false;
    }
}
