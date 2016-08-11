<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 5/18/15
 * Time: 12:14 PM
 */

namespace app\components\rocket;


use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\helpers\Inflector;

class RFSluggableBehavior extends SluggableBehavior
{

    public $length = 90;
    public $keepWords = true;

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        $isNewSlug = true;

        if ($this->attribute !== null) {
            $attributes = (array)$this->attribute;
            /* @var $owner BaseActiveRecord */
            $owner = $this->owner;
            if (!empty($owner->{$this->slugAttribute})) {
                $isNewSlug = false;
                if (!$this->immutable) {
                    foreach ($attributes as $attribute) {
                        if ($owner->isAttributeChanged($attribute)) {
                            $isNewSlug = true;
                            break;
                        }
                    }
                }
            }

            if ($isNewSlug) {
                $slugParts = [];
                foreach ($attributes as $attribute) {
                    $slugParts[] = $owner->{$attribute};
                }
                $slug = Inflector::slug(implode('-', $slugParts));
            } else {
                $slug = $owner->{$this->slugAttribute};
            }
        } else {
            $slug = parent::getValue($event);
        }

        if ($this->ensureUnique && $isNewSlug) {
            $baseSlug = $slug;
            $iteration = 0;
            while (!$this->validateSlug($slug)) {
                $iteration++;
                $slug = $this->generateUniqueSlug($baseSlug, $iteration);
            }
        }
        if ($this->keepWords && strlen($slug) > $this->length) {
            $slug = substr($slug, 0, strpos($slug, '-', $this->length));
        } else {
            $slug = substr($slug, 0, $this->length);
        }

        return $slug;
    }
}