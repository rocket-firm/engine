<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 9/19/14
 * Time: 16:43
 */

namespace rocketfirm\engine\modules\languages\front\components;


use rocketfirm\engine\modules\languages\models\Languages;
use yii\base\Widget;

class LanguageWidget extends Widget
{

    public function run()
    {
        $model = Languages::find()->where(['is_active' => 1])->all();

        return $this->render('languageWidget', ['model' => $model]);
    }
}
