<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Dispositivo */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Dispositivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['borrar-historial']);
$id = $model->id;

$js = <<<EOT
    $('#borrarHistorial').click(function () {
        $.ajax({
            url: "$url",
            type: 'POST',
            data: { "id": "$id" },
            success: function (data, status, xhr) {
                $('#historial').empty();
            }
        });
    });
EOT;

$this->registerJs($js);
?>
<div class="dispositivo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
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
            'marca_disp',
            'modelo_disp',
            'ubicacion',
        ],
    ]) ?>

    <h2>Historial de movimientos</h2>

    <?= GridView::widget([
        'options' => [
            'id' => 'historial',
            'class' => 'grid-view',
        ],
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getRegistros(),
        ]),
        'columns' => [
            [
                'attribute' => 'origen_id',
                'value' => function ($reg, $widget) {
                    if ($reg->origen_ord_id !== null) {
                        return $reg->origenOrd->nombre;
                    } else {
                        return $reg->origenAula->den_aula;
                    }
                },
                'label' => 'Origen',
            ],
            [
                'attribute' => 'destino_id',
                'value' => function ($reg, $widget) {
                    if ($reg->destino_ord_id !== null) {
                        return $reg->destinoOrd->nombre;
                    } else {
                        return $reg->destinoAula->den_aula;
                    }
                },
                'label' => 'Destino',
            ],
            'created_at:datetime',
        ],
    ]) ?>

    <?= Html::button(
        'Borrar historial', [
            'class' => 'btn btn-danger',
            'id' => 'borrarHistorial',
        ]) ?>

</div>
