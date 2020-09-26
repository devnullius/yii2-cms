<?php
declare(strict_types=1);

namespace devnullius\cms\repositories;

use devnullius\cms\entities\Page;
use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;

final class PageRepository
{
    public function get(int $id): Page
    {
        if (!$page = Page::findOne($id)) {
            throw new NotFoundException('Page not found.');
        }

        return $page;
    }

    public function save(Page $page): void
    {
        if (!$page->save()) {
            throw new RuntimeException('Saving error.');
        }
    }

    public function remove(Page $page): void
    {
        try {
            if (!$page->delete()) {
                throw new RuntimeException('Removing error.');
            }
        } catch (StaleObjectException $e) {
        } catch (Throwable $e) {
        }
    }
}
