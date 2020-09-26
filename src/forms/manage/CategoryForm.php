<?php
declare(strict_types=1);

namespace devnullius\cms\forms\manage;

use devnullius\cms\entities\Category;
use devnullius\cms\validators\SlugValidator;
use devnullius\helper\forms\CoreFormTrait;
use elisdn\compositeForm\CompositeForm;

/**
 * @property MetaForm $meta;
 */
class CategoryForm extends CompositeForm
{
    use CoreFormTrait;
    public $name;
    public $slug;
    public $title;
    public $description;
    public $sort;

    private $_category;

    public function __construct(Category $category = null, $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->sort = $category->sort;
            $this->meta = new MetaForm($category->meta);
            $this->_category = $category;
        } else {
            $this->meta = new MetaForm();
            $this->sort = Category::find()->max('sort') + 1;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['description'], 'toString'],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}
