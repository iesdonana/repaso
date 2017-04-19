<?php

use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ordenador */

$this->title = $model->marca_ord . ' ' . $model->modelo_ord;
$this->params['breadcrumbs'][] = ['label' => 'Ordenadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['ordenadores/borrar-historial']);
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
<div class="ordenador-view">

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
            'marca_ord',
            'modelo_ord',
            'aula.den_aula:text:Aula',
        ],
    ]) ?>

    <h2>Dispositivos que contiene</h2>

    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getDispositivos(),
        ]),
        'columns' => [
            'marca_disp',
            'modelo_disp',
            [
                'class' => ActionColumn::className(),
                'controller' => 'dispositivos',
            ],
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
            'origen.den_aula:text:Origen',
            'destino.den_aula:text:Destino',
            'created_at:datetime'
        ],
    ]) ?>

    <?= Html::button(
        'Borrar historial', [
            'class' => 'btn btn-danger',
            'id' => 'borrarHistorial',
        ]) ?>

</div>
