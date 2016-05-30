<?php

/**
 * File containing the Cache PurgeClientInterface class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Cache;

interface PurgeClientInterface
{
    /**
     * Triggers the cache purge $cacheElements.
     *
     * @deprecate Since 6.4
     *
     * @param mixed $cacheElements Cache resource(s) to purge (e.g. array of URI to purge in a reverse proxy)
     */
    public function purge($locationIds);

    /**
     * Triggers the cache purge/invalidation of cache by $tags.
     *
     * @since 6.4
     *
     * @param string[] $tags Tags that
     */
    public function purgeByTags(array $tags);

    /**
     * Purges all content elements currently in cache.
     *
     * @deprecated Use cache:clear, with multi tagging theoretically there shouldn't be need to delete all anymore from core.
     */
    public function purgeAll();
}
