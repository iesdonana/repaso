<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dispositivo */

$this->title = 'Create Dispositivo';
$this->params['breadcrumbs'][] = ['label' => 'Dispositivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispositivo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
