<ul class="inline">
    <?php foreach ($model as $item) { ?>
        <?php if ($item->id == \app\modules\languages\models\Languages::getCurrent()->id) { ?>
            <li><span data-lang="<?= $item->code ?>"><span class="icl_lang_sel_current"><?=$item->title?></span></span></li>
        <?php } else { ?>
            <li>
                <a href="/<?= $item->code ?>"><span data-lang="<?= $item->code ?>"><span class="icl_lang_sel_current"><?=$item->title?></span></span></a>
            </li>
        <?php } ?>

    <?php } ?>
</ul>
