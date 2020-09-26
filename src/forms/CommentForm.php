<?php
declare(strict_types=1);

namespace devnullius\cms\forms;

use devnullius\helper\forms\CoreFormTrait;
use yii\base\Model;

class CommentForm extends Model
{
    use CoreFormTrait;
    public $parentId;
    public $text;

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
