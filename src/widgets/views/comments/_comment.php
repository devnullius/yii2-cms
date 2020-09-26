<?php

use devnullius\cms\widgets\CommentView;
use yii\base\InvalidConfigException;

assert($item instanceof CommentView);

?>
<div class="comment-item" data-id="<?= $item->comment->id ?>">
    <div class="panel panel-default">
        <div class="panel-body">
            <p class="comment-content">
                <?php if ($item->comment->isActive()) : ?>
                    <?= Yii::$app->formatter->asNtext($item->comment->text) ?>
                <?php else : ?>
                    <i>Comment is deleted.</i>
                <?php endif; ?>
            </p>
            <div>
                <div class="pull-left">
                    <?php
                    try {
                        Yii::$app->formatter->asDatetime($item->comment->created_at);
                    } catch (InvalidConfigException $e) {
                        Yii::$app->errorHandler->logException($e);
                        echo $e->getMessage();
                    } ?>
                </div>
                <div class="pull-right">
                    <span class="comment-reply">Reply</span>
                </div>
            </div>
        </div>
    </div>
    <div class="margin">
        <div class="reply-block"></div>
        <div class="comments">
            <?php foreach ($item->children as $children) : ?>
                <?= $this->render('_comment', ['item' => $children]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
