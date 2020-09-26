<?php

use devnullius\cms\entities\post\Post;
use devnullius\cms\forms\manage\post\PostForm;
use yii\web\View;

assert($this instanceof View);
assert($post instanceof Post);
assert($model instanceof PostForm);

$this->title = 'Update Post: ' . $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $post->title, 'url' => ['view', 'id' => $post->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
