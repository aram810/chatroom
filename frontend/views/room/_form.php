<?php

use noam148\imagemanager\components\ImageManagerInputWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Room */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="room-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if (!Yii::$app->user->isGuest): ?>

        <?= $form->field($model, 'access_level')->dropDownList([0 => 'Public', 1 => 'Private']) ?>

        <?= $form->field($model, 'access_with_token')->checkbox() ?>

    <?php endif; ?>

    <?= $form->field($model, 'image_id')->widget(ImageManagerInputWidget::className(), [
	    'aspectRatio' => (16/9),
	    'showPreview' => true,
	    'showDeletePickedImageConfirm' => true,
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
