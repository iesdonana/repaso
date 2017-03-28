<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ordenador */

$this->title = 'Create Ordenador';
$this->params['breadcrumbs'][] = ['label' => 'Ordenadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ordenador-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
