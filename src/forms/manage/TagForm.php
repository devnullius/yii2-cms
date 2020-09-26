<?php
declare(strict_types=1);

namespace devnullius\cms\forms\manage;

use devnullius\cms\entities\Tag;
use devnullius\cms\validators\SlugValidator;
use devnullius\helper\forms\CoreFormTrait;
use yii\base\Model;

class TagForm extends Model
{
    use CoreFormTrait;
    public $name;
    public $slug;

    private $_tag;

    public function __construct(Tag $tag = null, $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->_tag = $tag;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name', 'slug'], 'toString'],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Tag::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null],
        ];
    }
}
