<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\components\UsuariosHelper;
use app\helpers\Mensaje;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Ordenadores', 'url' => ['/ordenadores/index']],
            ['label' => 'Dispositivos', 'url' => ['/dispositivos/index']],
            ['label' => 'Aulas', 'url' => ['/aulas/index']],
            UsuariosHelper::isAdmin() ? (
                ['label' => 'Usuarios', 'url' => ['usuarios/index']]
            ) : '',
            UsuariosHelper::isGuest() ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . UsuariosHelper::get('nombre') . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php if (Mensaje::hayExito()): ?>
            <?= Alert::widget([
                'options' => [
                    'class' => 'alert-success',
                ],
                'body' => Mensaje::exito(),
            ]) ?>
        <?php endif; ?>
        <?php if (Mensaje::hayFracaso()): ?>
            <?= Alert::widget([
                'options' => [
                    'class' => 'alert-danger',
                ],
                'body' => Mensaje::fracaso(),
            ]) ?>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
