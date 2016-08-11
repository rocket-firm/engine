<?php if ($data['type'] == 'code') { ?>
    <?= $data['content'] ?>
<?php } else {
    if ($data['type'] == 'swf') { ?>
        <a href="<?= $data['url'] ?>"
           class='swf-banner'
            <?php /**id='swf-banner-<?= $data['id'] ?>'**/ ?>
           data-swf="<?= $data['swf'] ?>"
           data-width="<?= $data['width'] ?>"
           data-height="<?= $data['height'] ?>"
            <?= !$data['is_internal'] ? 'target="_blank"' : '' ?>
            ><?= \yii\helpers\Html::img($data['image']) ?>
        </a>
    <?php } else { ?>
        <a class="link-block"
           href="<?= $data['url'] ?>" <?= !$data['is_internal'] ? 'target="_blank" rel="nofollow"' : '' ?>>
            <?= \yii\helpers\Html::img($data['image']) ?>
        </a>
    <?php }
} ?>

