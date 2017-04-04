<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Dispositivo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dispositivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispositivo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'marca_disp',
            'modelo_disp',
            'ordenador_id',
            'aula_id',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getRegistros(),
        ]),
        'columns' => [
            [
                'attribute' => 'origen_id',
                'value' => function ($model, $widget) {
                    if ($model->origen_ord_id !== null) {
                        return $model->origen_ord_id;
                    } else {
                        return $model->origen_aula_id;
                    }
                },
                'label' => 'Origen'
            ],
            [
                'attribute' => 'destino_id',
                'value' => function ($model, $widget) {
                    if ($model->destino_ord_id !== null) {
                        return $model->destino_ord_id;
                    } else {
                        return $model->destino_aula_id;
                    }
                },
                'label' => 'Destino'
            ],
            'created_at:datetime'
        ],
    ]) ?>

</div>
