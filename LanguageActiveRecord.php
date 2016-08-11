<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 10/28/14
 * Time: 12:24 PM
 */

namespace app\components;


use app\modules\languages\models\Languages;
use yii\console\Application;

class LanguageActiveRecord extends ActiveRecord
{

    public function beforeValidate()
    {
        parent::beforeValidate();

        $this->lang_id = Languages::getAdminCurrent()->id;

        return true;
    }
}
