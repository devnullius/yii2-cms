<?php

use devnullius\cms\entities\Category;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

assert($this instanceof View);
assert($dataProvider instanceof DataProviderInterface);
assert($category instanceof Category);

$this->title = 'Blog';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>
