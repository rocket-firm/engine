<?php
/**
 * Created by PhpStorm.
 * User: evgeniy
 * Date: 7/16/14
 * Time: 11:34
 */

namespace rocketfirm\engine;

use rocketfirm\engine\assets\AppAsset;
use rocketfirm\engine\actions\CropImage;
use rocketfirm\engine\actions\UploadAsync;
use rocketfirm\engine\grid\AddMenuItemAction;
use rocketfirm\engine\rocket\RFSendMail;
use rocketfirm\engine\config\models\Config;
use dosamigos\grid\ToggleAction;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\View;

class RFAController extends \yii\web\Controller
{
    use RFSendMail;
    public $layout = '//admin/main';
    protected $_modelName = null;
    public $siteConfig;

    public $allowedRoles = [];

    public function init()
    {
        parent::init();

        $this->getView()->on(View::EVENT_BEFORE_RENDER, function () {
            AppAsset::register($this->getView());
        });
    }

    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::className(),
                'except' => ['login', 'error', 'logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function () {
                            if (!\Yii::$app->getUser()->getIsGuest()) {
                                if (empty(\Yii::$app->controller->allowedRoles) || !is_array(\Yii::$app->controller->allowedRoles)) {

                                    return \Yii::$app->user->getIdentity()->checkRole(['admin', 'author', 'editor']);
                                }
                                return \Yii::$app->getUser()->getIdentity()->checkRole(\Yii::$app->controller->allowedRoles);
                            }
                            return false;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'crop-image' => [
                'class' => CropImage::className()
            ],
            'upload-async' => [
                'class' => UploadAsync::className()
            ],
            'toggle' => [
                'class' => ToggleAction::className(),
                'modelClass' => $this->getModelName(),
                'onValue' => 1,
                'offValue' => 0
            ],
            'add-menu-item' => [
                'class' => AddMenuItemAction::className()
            ]

        ];
    }

    public function getModelName()
    {
        if ($this->_modelName === null) {
            $this->_modelName = ucfirst($this->id);
        }

        return $this->_modelName;
    }

}
