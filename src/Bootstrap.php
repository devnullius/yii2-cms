<?php
declare(strict_types=1);

namespace devnullius\cms;

use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\data\Pagination;
use yii\web\Application as WebApplication;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app): void
    {
        if ($app instanceof WebApplication && $cmsModule = Yii::$app->getModule('cms')) {
            $moduleId = $cmsModule->id;
            $app->getUrlManager()->addRules([
                'cms' => $moduleId . '/admin/page/index',
            ], false);
        }
    }
}
