<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Language */

$this->title = 'Create Language';
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
