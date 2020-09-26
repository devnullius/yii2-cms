<?php

use yii\data\DataProviderInterface;
use yii\web\View;
use yii\widgets\ListView;

assert($this instanceof View);
assert($dataProvider instanceof DataProviderInterface);

try {
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{pager}",
        'itemView' => '_post',
    ]);
} catch (Exception $e) {
    Yii::$app->errorHandler->logException($e);
    echo $e->getMessage();
}
