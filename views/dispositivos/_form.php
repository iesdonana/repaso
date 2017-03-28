<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Dispositivo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dispositivo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'marca_disp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modelo_disp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ordenador_id')->textInput() ?>

    <?= $form->field($model, 'aula_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
