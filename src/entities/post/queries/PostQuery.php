<?php
declare(strict_types=1);

namespace devnullius\cms\entities\post\queries;

use devnullius\cms\entities\post\Post;
use yii\db\ActiveQuery;

/**
 * Class PostQuery
 *
 * @package devnullius\cms\entities\Blog\Post\queries
 * @see     Post
 */
final class PostQuery extends ActiveQuery
{
    /**
     * @param string|null $alias
     *
     * @return $this
     */
    public function active(string $alias = null): self
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Post::STATUS_ACTIVE,
        ]);
    }
}
