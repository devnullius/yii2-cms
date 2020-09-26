<?php

use devnullius\cms\forms\manage\post\PostForm;
use yii\web\View;

assert($this instanceof View);
assert($model instanceof PostForm);

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
