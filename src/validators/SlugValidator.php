<?php
declare(strict_types=1);

namespace devnullius\cms\validators;

use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9_-]*$#s';
    public $message = 'Only [a-z0-9_-] symbols allowed.';
}
