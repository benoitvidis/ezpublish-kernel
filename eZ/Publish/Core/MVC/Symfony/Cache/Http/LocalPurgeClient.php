<?php

/**
 * File containing the LocalPurgeClient class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Cache\Http;

use eZ\Publish\Core\MVC\Symfony\Cache\PurgeClientInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * LocalPurgeClient emulates an Http PURGE request received by the cache store.
 * Handy for mono-server.
 */
class LocalPurgeClient implements PurgeClientInterface
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Cache\Http\ContentPurger
     */
    protected $cacheStore;

    public function __construct(RequestAwarePurger $cacheStore)
    {
        $this->cacheStore = $cacheStore;
    }

    /**
     * @inheritdoc
     */
    public function purge($locationIds)
    {
        if (empty($locationIds)) {
            return;
        }

        if (!is_array($locationIds)) {
            $locationIds = array($locationIds);
        }

        $purgeRequest = Request::create('http://localhost/', 'BAN');
        $purgeRequest->headers->set('X-Location-Id', '(' . implode('|', $locationIds) . ')');
        $this->cacheStore->purgeByRequest($purgeRequest);
    }

    /**
     * @inheritdoc
     */
    public function purgeByTags(array $tags)
    {
        if (empty($tags)) {
            return;
        }

        // TODO: Need to switch to purge and not ban to be able to use soft purge.
        $purgeRequest = Request::create('http://localhost/', 'BAN');
        $purgeRequest->headers->set('xkey', '(' . implode('|', $tags) . ')');
        $this->cacheStore->purgeByRequest($purgeRequest);
    }

    /**
     * @inheritdoc
     */
    public function purgeAll()
    {
        $this->cacheStore->purgeAllContent();
    }
}
