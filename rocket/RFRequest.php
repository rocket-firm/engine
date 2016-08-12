<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/21/14
 * Time: 18:14
 */

namespace rocketfirm\engine\rocket;


use rocketfirm\engine\modules\languages\models\Languages;
use yii\web\Request;

class RFRequest extends Request
{

    protected function resolveRequestUri()
    {
        $lang_prefix = null;
        $requestUri = parent::resolveRequestUri();
        $requestUriToList = explode('/', $requestUri);
        $lang_url = isset($requestUriToList[1]) ? $requestUriToList[1] : null;

        Languages::setCurrent($lang_url);

        if ($lang_url !== null && $lang_url === Languages::getCurrent()->code && strpos($requestUri, Languages::getCurrent()->code) === 1) {
            $requestUri = substr($requestUri, strlen(Languages::getCurrent()->code) + 1);
        }
        return $requestUri;
    }
}
