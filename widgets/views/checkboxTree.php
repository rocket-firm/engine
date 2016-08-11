<?php

use \yii\helpers\Html;

?>
<div class="has-error">
    <?= Html::error($model, $attributeName, ['class' => 'help-block']) ?>
</div>
<div class="checkbox-tree">
    <?php
    $level = 0;

    foreach ($categories as $n => $category) {
        if ($category->level == $level) {
            echo Html::endTag('li') . "\n";
        } elseif ($category->level > $level) {
            echo Html::beginTag('ul') . "\n";
        } else {
            echo Html::endTag('li') . "\n";

            for ($i = $level - $category->level; $i; $i--) {
                echo Html::endTag('ul') . "\n";
                echo Html::endTag('li') . "\n";
            }
        }

        echo Html::beginTag('li');
        echo Html::activeCheckbox($model, $attributeName . '[' . $category->id . ']',
            ['value' => $category->id, 'label' => ' ' . $category->title]);
        $level = $category->level;
    }

    for ($i = $level; $i; $i--) {
        echo Html::endTag('li') . "\n";
        echo Html::endTag('ul') . "\n";
    }

    ?>
</div>

