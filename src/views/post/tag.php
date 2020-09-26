<?php

use devnullius\cms\entities\Tag;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

assert($this instanceof View);
assert($dataProvider instanceof DataProviderInterface);
assert($tag instanceof Tag);

$this->title = 'Posts with tag ' . $tag->name;

$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $tag->name;
?>

<h1>Posts with tag &laquo;<?= Html::encode($tag->name) ?>&raquo;</h1>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>


