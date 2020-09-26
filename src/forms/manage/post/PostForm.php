<?php
declare(strict_types=1);

namespace devnullius\cms\forms\manage\post;

use devnullius\cms\entities\Category;
use devnullius\cms\entities\post\Post;
use devnullius\cms\forms\manage\MetaForm;
use devnullius\cms\validators\SlugValidator;
use devnullius\helper\forms\CoreFormTrait;
use elisdn\compositeForm\CompositeForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property MetaForm $meta
 * @property TagsForm $tags
 */
class PostForm extends CompositeForm
{
    use CoreFormTrait;
    public $categoryId;
    public $title;
    public $description;
    public $content;
    public $photo;

    public function __construct(Post $post = null, $config = [])
    {
        if ($post) {
            $this->categoryId = $post->category_id;
            $this->title = $post->title;
            $this->description = $post->description;
            $this->content = $post->content;
            $this->meta = new MetaForm($post->meta);
            $this->tags = new TagsForm($post);
        } else {
            $this->meta = new MetaForm();
            $this->tags = new TagsForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['categoryId', 'title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['categoryId'], 'integer'],
            [['categoryId'], 'toInt'],
            [['description', 'content'], 'string'],
            [['description', 'content'], 'toString'],
            [['photo'], 'image'],
        ];
    }

    public function categoriesList(): array
    {
        return ArrayHelper::map(Category::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->photo = UploadedFile::getInstance($this, 'photo');

            return true;
        }

        return false;
    }

    protected function internalForms(): array
    {
        return ['meta', 'tags'];
    }
}
