<?php
declare(strict_types=1);

namespace devnullius\cms\urls;

use devnullius\cms\entities\Page;
use devnullius\cms\readModels\PageReadRepository;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;
use yii\web\UrlNormalizerRedirectException;
use yii\web\UrlRuleInterface;

class PageUrlRule extends BaseObject implements UrlRuleInterface
{
    private PageReadRepository $repository;
    private Cache $cache;

    public function __construct(PageReadRepository $repository, Cache $cache, $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
        $this->cache = $cache;
    }

    public function parseRequest($manager, $request)
    {
        $path = $request->pathInfo;

        $result = $this->cache->getOrSet(['page_route', 'path' => $path], function () use ($path) {
            if (!$page = $this->repository->findBySlug($this->getPathSlug($path))) {
                return ['id' => null, 'path' => null];
            }

            return ['id' => $page->id, 'path' => $this->getPagePath($page)];
        });

        if (empty($result['id'])) {
            return false;
        }

        if ($path !== $result['path']) {
            throw new UrlNormalizerRedirectException(['page/view', 'id' => $result['id']], 301);
        }

        return ['page/view', ['id' => $result['id']]];
    }

    private function getPathSlug(string $path): string
    {
        $chunks = explode('/', $path);

        return end($chunks);
    }

    private function getPagePath(Page $page): string
    {
        $chunks = ArrayHelper::getColumn($page->getParents()->andWhere(['>', 'depth', 0])->all(), 'slug');
        $chunks[] = $page->slug;

        return implode('/', $chunks);
    }

    public function createUrl($manager, $route, $params)
    {
        if ($route === 'page/view') {
            if (empty($params['id'])) {
                throw new InvalidArgumentException('Empty id.');
            }
            $id = $params['id'];

            $url = $this->cache->getOrSet(['page_route', 'id' => $id], function () use ($id) {
                if (!$page = $this->repository->find($id)) {
                    return null;
                }

                return $this->getPagePath($page);
            });

            if (!$url) {
                throw new InvalidArgumentException('An undefined id.');
            }

            unset($params['id']);
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }

            return $url;
        }

        return false;
    }
}
