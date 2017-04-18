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
        <?= Html::a('Crear dispositivo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'marca_disp',
            'modelo_disp',
            [
                'attribute' => 'ubicacion',
                'value' => function ($model, $widget) {
                    if ($model->ordenador_id !== null) {
                        return Html::a(
                            Html::encode($model->ubicacion),
                            ['ordenadores/view', 'id' => $model->ordenador_id]
                        );
                    } else {
                        return Html::a(
                            Html::encode($model->ubicacion),
                            ['aulas/view', 'id' => $model->aula_id]
                        );
                    }
                },
                'format' => 'html',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
