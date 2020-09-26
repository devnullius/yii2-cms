<?php
declare(strict_types=1);

namespace devnullius\cms\entities\behaviors;

use devnullius\cms\entities\Meta;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

final class MetaBehavior extends Behavior
{
    public string $attribute = 'meta';
    public string $jsonAttribute = 'meta_json';

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onAfterFind(Event $event): void
    {
        $model = $event->sender;
        $meta = Json::decode($model->getAttribute($this->jsonAttribute));
        $model->{$this->attribute} = new Meta(
            (string)ArrayHelper::getValue($meta, 'title'),
            (string)ArrayHelper::getValue($meta, 'description'),
            (string)ArrayHelper::getValue($meta, 'keywords')
        );
    }

    public function onBeforeSave(Event $event): void
    {
        $model = $event->sender;
        $model->setAttribute('meta_json', Json::encode([
            'title' => (string)$model->{$this->attribute}->title,
            'description' => (string)$model->{$this->attribute}->description,
            'keywords' => (string)$model->{$this->attribute}->keywords,
        ]));
    }
}
