<?php

namespace rocketfirm\engine\modules\banners\models;

use rocketfirm\engine\ActiveRecord;
use rocketfirm\engine\traits\Uploadable;
use rocketfirm\engine\modules\pages\models\Pages;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property integer $priority
 * @property string $title
 * @property string $content
 * @property string $start_date
 * @property string $end_date
 * @property integer $is_active
 * @property string $url
 * @property string $image
 * @property integer $type
 * @property string $swf
 * @property integer $swf_width
 * @property integer $swf_height
 * @property string $bg_color
 * @property string $create_date
 * @property string $update_date
 *
 * @property BannerPlaces[] $bannerPlaces
 */
class Banners extends ActiveRecord
{
    use Uploadable;

    const TYPE_BANNER_1 = 1;
    const TYPE_BANNER_2 = 2;
    const TYPE_BANNER_3 = 3;
    const TYPE_BANNER_4 = 4;
    const TYPE_BANNER_5 = 5;
    const TYPE_BANNER_6 = 6;
    const TYPE_BANNER_7 = 7;
    const TYPE_BANNER_8 = 8;
    const TYPE_BANNER_9 = 9;

    public static $types = array(
        self::TYPE_BANNER_1 => '1440x90 в шапке',
        self::TYPE_BANNER_2 => '240x400 справа',
        self::TYPE_BANNER_3 => '700x90 под новостями',
        self::TYPE_BANNER_4 => '700x90 после новостей',
        self::TYPE_BANNER_5 => '240x400 справа в статьях',
        self::TYPE_BANNER_6 => '700x90',
        self::TYPE_BANNER_7 => '240x400 слева в статьях',
        self::TYPE_BANNER_8 => '700x90',
        self::TYPE_BANNER_9 => '700x90',
    );

    public static $sizes = array(
        self::TYPE_BANNER_1 => [1440, 90],
        self::TYPE_BANNER_2 => [240, 400],
        self::TYPE_BANNER_3 => [700, 90],
        self::TYPE_BANNER_4 => [700, 90],
        self::TYPE_BANNER_5 => [240, 400],
        self::TYPE_BANNER_6 => [700, 90],
        self::TYPE_BANNER_7 => [240, 400],
        self::TYPE_BANNER_8 => [700, 90],
        self::TYPE_BANNER_9 => [700, 90],
    );

    /**
     * @var $imageFile \yii\web\UploadedFile
     */
    public $imageFile;

    public $oldImage;
    public $oldSwf;

    /**
     * @var $swfFile \yii\web\UploadedFile
     */
    public $swfFile;

    public $positions;


    /**
     * Типы страниц
     */
    const PAGE_ALL = 0;

    public static $pageTypes = array(
        self::PAGE_ALL => 'Все страницы',
    );

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priority', 'is_active', 'type', 'swf_width', 'swf_height'], 'integer'],
            [['title', 'start_date', 'type'], 'required'],
            [['start_date', 'end_date', 'create_date', 'update_date'], 'safe'],
            [['title', 'content', 'url', 'image', 'swf', 'bg_color'], 'string', 'max' => 255],
            [['imageFile'], 'image', 'skipOnEmpty' => true],
            [['swfFile'], 'file', 'extensions' => 'swf', 'skipOnEmpty' => true],
            [['imageFile'], 'checkUploadFile', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['positions'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('banners', 'ID'),
            'priority' => Yii::t('banners', 'Приоритет'),
            'title' => Yii::t('banners', 'Наименование'),
            'content' => Yii::t('banners', 'Содержимое'),
            'start_date' => Yii::t('banners', 'Дата начала показа'),
            'end_date' => Yii::t('banners', 'Дата окончания показа'),
            'is_active' => Yii::t('banners', 'Активность'),
            'url' => Yii::t('banners', 'URL'),
            'image' => Yii::t('banners', 'Изображение'),
            'imageFile' => Yii::t('banners', 'Изображение'),
            'type' => Yii::t('banners', 'Тип'),
            'swf' => Yii::t('banners', 'Флэш банер'),
            'swfFile' => Yii::t('banners', 'Флэш банер'),
            'swf_width' => Yii::t('banners', 'Ширина флэша'),
            'swf_height' => Yii::t('banners', 'Высота флэша'),
            'bg_color' => Yii::t('banners', 'Цвет фона'),
            'positions' => Yii::t('banners', ' Расположение банера')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerPlaces()
    {
        return $this->hasMany(BannerPlaces::className(), ['banner_id' => 'id']);
    }

    public function checkUploadFile($attribute, $params)
    {
        if ($this->imageFile && !$this->url) {
            $this->addError('url', 'Нужно ввести ссылку');
        }

        if (!$this->image && !$this->imageFile && !$this->content) {
            $msg = "Введите код баннера либо загрузите изображение";
            $this->addError('image', $msg);
            $this->addError('content', $msg);
        }
    }

    public function afterFind()
    {
        $this->positions = ArrayHelper::map($this->getBannerPlaces()->asArray()->all(), 'page', 'page');
        $this->oldImage = $this->image;
        $this->oldSwf = $this->swf;

        parent::afterFind();
        return true;
    }

    public function beforeValidate()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'image');
        $this->swfFile = UploadedFile::getInstance($this, 'swf');

        if (empty($this->imageFile)) {
            $this->image = $this->oldImage;
        }

        if (empty($this->swfFile)) {
            $this->swf = $this->oldSwf;
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {

        if ($this->swfFile instanceof UploadedFile) {
            $this->saveFile($this->swfFile, 'swf', false);
        }

        if ($this->imageFile instanceof UploadedFile) {
            $this->saveFile($this->imageFile, 'image', false);
        }


        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {

        $model = new BannerPlaces;
        $model->deleteAll(['banner_id' => $this->id]);
        foreach ($this->positions as $position) {
            $model = new BannerPlaces;
            $model->banner_id = $this->id;
            $model->place = $position;
            $model->page = $position;

            $this->link('bannerPlaces', $model);
        }

        parent::afterSave($insert, $changedAttributes);

        return true;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_date', 'update_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_date']
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param $page int page type constant from PageLayout class
     * @return array banners
     */
    public static function getForPage($page)
    {
        $cacheDependency = new DbDependency;
        $cacheDependency->sql = "SELECT MAX(update_date), COUNT(id) FROM banners";
        $cacheDependency->params = [];

        $data = Yii::$app->db->cache(function () use ($page) {
            return Banners::find()->select("banners.id, banners.content, banners.image, banners.bg_color, banners.url, banners.type, banners.swf, banners.swf_width, banners.swf_height")
                ->joinWith("bannerPlaces")
                ->andOnCondition("banner_places.page=:page OR banner_places.page=:anyPage", [
                    ":page" => $page,
                    ":anyPage" => Banners::PAGE_ALL
                ])
                ->andOnCondition('start_date < NOW() AND (end_date > NOW() OR end_date IS NULL)')
                ->where(['is_active' => 1])
                //->orderBy('RAND()')
                ->asArray()->all();
        }, 0, $cacheDependency);


        $banners = array();
        foreach ($data as $banner) {
            $orientation = $banner['type'];

            if ($banner['content']) {
                $type = 'code';
            } else {
                if ($banner['swf']) {
                    $type = 'swf';
                } else {
                    $type = 'image';
                }
            }
            if (!isset($banners[$orientation])) {
                switch ($type) {
                    case 'image':
                        $banners[$orientation] = array(
                            'id' => $banner['id'],
                            'image' => '/media/banners/' . $banner['image'],
                            'url' => $banner['url'],
                            'type' => $type,
                            'orientation' => $orientation,
                            'bg_color' => $banner['bg_color'],
                            'is_internal' => self::isInternal($banner['url'])
                        );
                        break;
                    case 'swf':
                        $banners[$orientation] = array(
                            'id' => $banner['id'],
                            'image' => '/media/banners/' . $banner['image'],
                            'swf' => '/media/banners/' . $banner['swf'],
                            'width' => (int)$banner['swf_width'],
                            'height' => (int)$banner['swf_height'],
                            'url' => $banner['url'],
                            'type' => $type,
                            'orientation' => $orientation,
                            'bg_color' => $banner['bg_color'],
                            'is_internal' => self::isInternal($banner['url'])
                        );
                        break;
                    default:
                        $banners[$orientation] = array(
                            'id' => $banner['id'],
                            'content' => $banner['content'],
                            'type' => $type,
                            'orientation' => $orientation,
                            'bg_color' => $banner['bg_color'],
                            'is_internal' => self::isInternal($banner['url'])
                        );
                        break;
                }
            }
        }

        return $banners;
    }

    public static function isInternal($url)
    {
        return false;//mb_strpos($url, Yii::$app->request->baseUrl) !== false;
    }
}
