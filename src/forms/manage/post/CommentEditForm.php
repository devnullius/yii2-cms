<?php
declare(strict_types=1);

namespace devnullius\cms\forms\manage\post;

use devnullius\cms\entities\post\Comment;
use devnullius\helper\forms\CoreFormTrait;
use yii\base\Model;

class CommentEditForm extends Model
{
    use CoreFormTrait;
    public $parentId;
    public $text;

    public function __construct(Comment $comment, $config = [])
    {
        $this->parentId = $comment->parent_id;
        $this->text = $comment->text;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['text'], 'required'],
            ['text', 'string'],
            ['text', 'toString'],
            ['parentId', 'integer'],
            ['parentId', 'toInt'],
        ];
    }
}
