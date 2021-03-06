<?php
declare(strict_types=1);

namespace devnullius\cms\forms\manage;

use devnullius\cms\entities\Meta;
use devnullius\helper\forms\CoreFormTrait;
use yii\base\Model;

class MetaForm extends Model
{
    use CoreFormTrait;
    public $title;
    public $description;
    public $keywords;

    public function __construct(Meta $meta = null, $config = [])
    {
        if ($meta) {
            $this->title = $meta->title;
            $this->description = $meta->description;
            $this->keywords = $meta->keywords;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['description', 'keywords'], 'string'],
            [['title', 'description', 'keywords'], 'toString'],
        ];
    }
}
