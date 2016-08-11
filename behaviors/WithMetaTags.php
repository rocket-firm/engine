<?php
namespace app\components\behaviors;

use app\components\ActiveRecord;
use app\components\traits\UploadableAsync;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use yii\validators\StringValidator;
use yii\validators\Validator;
use yii\web\UploadedFile;

/**
 */
class WithMetaTags extends Behavior
{
    public static $metaFields = [
        'meta_description',
        'meta_keywords',
        'meta_image',
        'meta_title'
    ];

    public static $metaImageSize = [500, 500];

    public $meta_keywords;
    public $meta_image;
    public $meta_description;
    public $meta_title;

    /**
     * @var UploadedFile
     */
    public $metaImageFile;

    /**
     * @var ActiveRecord|UploadableAsync
     */
    public $owner;

    public function attach($owner)
    {
        parent::attach($owner);


        $stringValidator = Validator::createValidator('string', $this->owner,
            ['meta_keywords', 'meta_description', 'meta_title'], ['max' => 255]);

        $imageValidator = Validator::createValidator('image', $this->owner,
            'metaImageFile', [
                'minWidth' => self::$metaImageSize[0],
                'minHeight' => self::$metaImageSize[1],
                'on' => 'upload'
            ]);

        $this->owner->validators[] = $stringValidator;
        $this->owner->validators[] = $imageValidator;

    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    public function afterValidate()
    {
        if ($this->owner->hasAsyncTempFile('metaImageFile')) {
            $this->owner->saveAsyncFile('metaImageFile', 'meta_image');
        }
        if (!$this->owner->meta_image) {
            $this->owner->meta_image = null;
        }
    }
}
