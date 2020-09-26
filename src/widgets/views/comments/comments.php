<?php

use devnullius\cms\entities\post\Post;
use devnullius\cms\forms\CommentForm;
use devnullius\cms\widgets\CommentView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

assert($this instanceof View);
assert($post instanceof Post);
assert($commentForm instanceof CommentForm);
/* @var $items CommentView[] */
/* @var $count integer */

?>

<div id="comments" class="inner-bottom-xs">
    <h2>Comments</h2>
    <?php foreach ($items as $item) : ?>
        <?= $this->render('_comment', ['item' => $item]) ?>
    <?php endforeach; ?>
</div>

<div id="reply-block" class="leave-reply">
    <?php $form = ActiveForm::begin([
        'action' => ['comment', 'id' => $post->id],
    ]); ?>

    <?= Html::activeHiddenInput($commentForm, 'parentId') ?>
    <?= $form->field($commentForm, 'text')->textarea(['rows' => 5]) ?>

    <div class="form-group">
        <?= Html::submitButton('Send own comment', ['class' => 'btn btn-inverse']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJs("
    jQuery(document).on('click', '#comments .comment-reply', function () {
        var link = jQuery(this);
        var form = jQuery('#reply-block');
        var comment = link.closest('.comment-item');
        jQuery('#commentform-parentid').val(comment.data('id'));
        form.detach().appendTo(comment.find('.reply-block:first'));
        return false;
    });
"); ?>


