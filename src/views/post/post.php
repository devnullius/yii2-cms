<?php

use devnullius\cms\entities\post\Post;
use devnullius\cms\widgets\CommentsWidget;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $post Post */

$this->title = $post->getSeoTitle();

$this->registerMetaTag(['name' => 'description', 'content' => $post->meta->description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $post->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $post->category->name, 'url' => ['category', 'slug' => $post->category->slug]];
$this->params['breadcrumbs'][] = $post->title;

$this->params['active_category'] = $post->category;

$tagLinks = [];
foreach ($post->tags as $tag) {
    $tagLinks[] = Html::a(Html::encode($tag->name), ['tag', 'slug' => $tag->slug]);
}
?>

<article>
    <h1><?= Html::encode($post->title) ?></h1>

    <p><span class="glyphicon glyphicon-calendar"></span> <?= Yii::$app->formatter->asDatetime($post->created_at); ?></p>

    <?php if ($post->photo) : ?>
        <p><img src="<?= Html::encode($post->getThumbFileUrl('photo', 'origin')) ?>" alt="" class="img-responsive"/></p>
    <?php endif; ?>

    <?= Yii::$app->formatter->asHtml($post->content, [
        'Attr.AllowedRel' => ['nofollow'],
        'HTML.SafeObject' => true,
        'Output.FlashCompat' => true,
        'HTML.SafeIframe' => true,
        'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
    ]) ?>
</article>

<p>Tags: <?= implode(', ', $tagLinks) ?></p>

<?php
try {
    echo CommentsWidget::widget([
        'post' => $post,
    ]);
} catch (Exception $e) {
    Yii::$app->errorHandler->logException($e);
    echo $e->getMessage();
} ?>


