<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DispositivoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dispositivos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispositivo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dispositivo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'marca_disp',
            'modelo_disp',
            'ordenador_id',
            'aula_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
