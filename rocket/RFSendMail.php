<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/24/14
 * Time: 14:50
 */

namespace rocketfirm\engine\rocket;

use rocketfirm\engine\modules\config\models\Config;
use yii\base\Behavior;
use yii\base\Component;

/**
 * Class sendMail
 * @package app\components\traits
 */
trait RFSendMail
{

    /**
     * Отправка почты
     *
     * @param $subject
     * @param $to
     * @param string $view
     * @param array $viewParams
     *
     * @return bool
     */
    public function sendMail($subject, $to, $view = '', $viewParams = [])
    {
        $mailerConfig = \Yii::$app->components['mailer'];

        $secures = [
            0 => null,
            1 => 'ssl',
            2 => 'tls'
        ];

        $to = explode(',', $to);

        $transportConfig = [
            'class' => 'Swift_SmtpTransport',
            'host' => Config::getParamValue('smtp_server', 'localhost'),
            'username' => Config::getParamValue('smtp_username'),
            'password' => Config::getParamValue('smtp_password'),
            'port' => Config::getParamValue('smtp_post', 25),
            'encryption' => Config::getParamValue('smtp_secure', 0)
        ];

        $mailerConfig['transport'] = $transportConfig;
        \Yii::$app->setComponents(['mailer' => $mailerConfig]);

        $result = \Yii::$app->getMailer()
            ->compose($view, $viewParams)
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom([Config::getParamValue('smtp_from_email') => Config::getParamValue('smtp_from_name')])
            ->send();

        return $result;
    }
}
