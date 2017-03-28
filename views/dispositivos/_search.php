<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DispositivoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dispositivo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'marca_disp') ?>

    <?= $form->field($model, 'modelo_disp') ?>

    <?= $form->field($model, 'ordenador_id') ?>

    <?= $form->field($model, 'aula_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
