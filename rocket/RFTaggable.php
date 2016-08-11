<?php
/**
 * Created by PhpStorm.
 * User: yevgeniy
 * Date: 4/15/15
 * Time: 3:48 PM
 */

namespace app\components\rocket;


use dosamigos\taggable\Taggable;

class RFTaggable extends Taggable
{

    /**
     * @param Event $event
     */
    public function afterSave($event)
    {
        if ($this->owner->{$this->attribute} === null) {
            return;
        }

        if (!$this->owner->getIsNewRecord()) {
            $this->beforeDelete($event);
        }

        $names = array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace(
                '/\s+/u',
                ' ',
                is_array($this->owner->{$this->attribute})
                    ? implode(',', $this->owner->{$this->attribute})
                    : $this->owner->{$this->attribute}
            ),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));


        $relation = $this->owner->getRelation($this->relation);
        $pivot = $relation->via->from[0];
        /** @var ActiveRecord $class */
        $class = $relation->modelClass;
        $rows = [];

        foreach ($names as $name) {
            $tag = $class::findOne([$this->name => $name]);

            if ($tag === null) {
                $tag = new $class();
                $tag->{$this->name} = $name;
            }

            $tag->{$this->frequency}++;

            if (!$tag->save(false, ['frequency'])) {
                continue;
            }

            $rows[] = [$this->owner->getPrimaryKey(), $tag->getPrimaryKey()];
        }

        if (!empty($rows)) {
            $this->owner->getDb()
                ->createCommand()
                ->batchInsert($pivot, [key($relation->via->link), current($relation->link)], $rows)
                ->execute();
        }
    }
}
