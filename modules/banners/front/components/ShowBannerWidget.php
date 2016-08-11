<?php

/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 8/26/14
 * Time: 17:44
 */
namespace rocketfirm\engine\modules\banners\front\components;

class ShowBannerWidget extends \yii\base\Widget
{

    public $banners;
    public $type;

    public function run()
    {


        if (empty($this->banners)) {
            $this->banners = \Yii::$app->controller->banners;
        }

        if (empty($this->type) || empty($this->banners)) {
            return false;
        }
        if (empty($this->banners[$this->type])) {
            return false;
        }
        return $this->render('showBanner', ['data' => $this->banners[$this->type]]);
    }
}
