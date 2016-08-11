<div class="form-group">
    <?= \yii\helpers\Html::label('Route', 'url', ['class' => 'control-label']); ?>
    <?= \yii\helpers\Html::textInput('url', $params['route'], ['class' => 'form-control']) ?>
</div>
<form action="#" onsubmit="javascript:return;" id="paramForm">
    <h4>Параметры</h4>
    <?php foreach ($params['params'] as $paramItem) { ?>
        <div class="form-group">
            <?= \yii\helpers\Html::label($paramItem, 'params', ['class' => 'control-label']); ?>
            <?= \yii\helpers\Html::textInput($paramItem, '', ['class' => 'form-control']) ?>
        </div>

    <?php
    }?>
    <div class="form-group">
        <?= \yii\helpers\Html::button('Вставить параметры',
            ['id' => 'insertParams', 'class' => 'btn btn-primary md-close', 'type' => 'button']); ?>
    </div>
</form>
<script>
    $(function () {
        $('#insertParams').on('click', function () {
            var params = $('#paramForm').serializeArray();

            var response = '{';
            $.each(params, function (key, value) {
                response += '"' + value.name + '":"' + value.value + '"';
                if (key < (params.length - 1)) {
                    response += ',';
                }
            });
            response += '}';
            $('#menuitems-params').val(response);
            unblockUI('#wrapper');
        });
    });
</script>


