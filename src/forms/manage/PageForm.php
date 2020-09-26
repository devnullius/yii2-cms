<?php
declare(strict_types=1);

namespace devnullius\cms\forms\manage;

use devnullius\cms\entities\Page;
use devnullius\cms\validators\SlugValidator;
use devnullius\helper\forms\CoreFormTrait;
use elisdn\compositeForm\CompositeForm;
use yii\helpers\ArrayHelper;

/**
 * @property MetaForm $meta;
 */
class PageForm extends CompositeForm
{
    use CoreFormTrait;
    public $title;
    public $slug;
    public $content;
    public $parentId;

    private $_page;

    public function __construct(Page $page = null, $config = [])
    {
        if ($page) {
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->parentId = $page->parent->id ?? 1;
            $this->meta = new MetaForm($page->meta);
            $this->_page = $page;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'slug'], 'required'],
            [['parentId'], 'integer'],
            [['parentId'], 'toInt'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['title', 'slug'], 'toString'],
            [['content'], 'string'],
            [['content'], 'toString'],
            ['slug', SlugValidator::class],
            [['slug'], 'unique', 'targetClass' => Page::class, 'filter' => $this->_page ? ['<>', 'id', $this->_page->id] : null],
        ];
    }

    public function parentsList(): array
    {
        return ArrayHelper::map(Page::find()->orderBy('lft')->asArray()->all(), 'id', function (array $page) {
            return ($page['depth'] > 1 ? str_repeat('-- ', $page['depth'] - 1) . ' ' : '') . $page['title'];
        });
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}
