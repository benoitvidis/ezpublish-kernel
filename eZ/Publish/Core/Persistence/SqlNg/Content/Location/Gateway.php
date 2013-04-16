<?php
/**
 * File containing the Location Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Location;

use eZ\Publish\SPI\Persistence\Content\Location\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\Location\CreateStruct;

/**
 * Base class for location gateways.
 */
abstract class Gateway
{
    /**
     * Constants for location states
     */
    const CREATED = 0;
    const PUBLISHED = 1;
    const DELETED = 2;

    /**
     * Returns an array with basic node data
     *
     * We might want to cache this, since this method is used by about every
     * method in the location handler.
     *
     * @param mixed $nodeId
     * @param int $status
     *
     * @return array
     */
    abstract public function getBasicNodeData( $nodeId, $status = self::PUBLISHED );

    /**
     * Returns an array with basic node data for the node with $remoteId
     *
     * @param mixed $remoteId
     *
     * @return array
     */
    abstract public function getBasicNodeDataByRemoteId( $remoteId );

    /**
     * Loads data for all Locations for $contentId, optionally only in the
     * subtree starting at $rootLocationId
     *
     * @param int $contentId
     * @param int $rootLocationId
     *
     * @return array
     */
    abstract public function loadLocationDataByContent( $contentId, $rootLocationId = null );

    /**
     * Find all content in the given subtree
     *
     * @param mixed $sourceId
     *
     * @return array
     */
    abstract public function getSubtreeContent( $sourceId );

    /**
     * Returns data for the first level children of the location identified by given $locationId
     *
     * @param mixed $locationId
     *
     * @return array
     */
    abstract public function getChildren( $locationId );

    /**
     * Update path strings to move nodes in the ezcontentobject_tree table
     *
     * This query can likely be optimized to use some more advanced string
     * operations, which then depend on the respective database.
     *
     * @param string $fromPathString
     * @param string $toPathString
     *
     * @return void
     */
    abstract public function moveSubtreeNodes( $fromPathString, $toPathString );

    /**
     * Publish locations for content and update the version
     *
     * @param mixed $contentId
     * @param mixed $versionNo
     *
     * @return void
     */
    abstract public function publishLocations( $contentId, $versionNo );

    /**
     * Sets a location to be hidden, and it self + all children to invisible.
     *
     * @param string $pathString
     */
    abstract public function hideSubtree( $pathString );

    /**
     * Sets a location to be unhidden, and self + children to visible unless a parent is hiding the tree.
     * If not make sure only children down to first hidden node is marked visible.
     *
     * @param string $pathString
     */
    abstract public function unHideSubtree( $pathString );

    /**
     * Swaps the content object being pointed to by a location object.
     *
     * Make the location identified by $locationId1 refer to the Content
     * referred to by $locationId2 and vice versa.
     *
     * @param mixed $locationId1
     * @param mixed $locationId2
     *
     * @return boolean
     */
    abstract public function swap( $locationId1, $locationId2 );

    /**
     * Creates a new location in given $parentNode
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Location\CreateStruct $createStruct
     * @param array $parentNode
     * @param int $status
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location
     */
    abstract public function create( CreateStruct $createStruct, $parentNodeData, $status );

    /**
     * Updates an existing location.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Location\UpdateStruct $location
     * @param int $locationId
     *
     * @return boolean
     */
    abstract public function update( UpdateStruct $location, $locationId );

    /**
     * Deletes ezcontentobject_tree row for given $locationId (node_id)
     *
     * @param mixed $locationId
     */
    abstract public function removeLocation( $locationId );

    /**
     * Sends a subtree identified by given $pathString to the trash.
     *
     * The associated content object is left untouched.
     *
     * @param string $pathString
     *
     * @return boolean
     */
    abstract public function trashSubtree( $pathString );

    /**
     * Returns a trashed location to normal state.
     *
     * Recreates the originally trashed location in the new position. If no new
     * position has been specified, it will be tried to re-create the location
     * at the old position. If this is not possible ( because the old location
     * does not exist any more) and exception is thrown.
     *
     * @param mixed $locationId
     * @param mixed $newParentId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location
     */
    abstract public function untrashLocation( $locationId, $newParentId = null );

    /**
     * Removes every entries in the trash.
     * Will NOT remove associated content objects nor attributes.
     *
     * Basically truncates ezcontentobject_trash table.
     *
     * @return void
     */
    abstract public function cleanupTrash();

    /**
     * Lists trashed items.
     * Returns entries from ezcontentobject_trash.
     *
     * @param int $offset
     * @param int $limit
     * @param array $sort
     *
     * @return array
     */
    abstract public function listTrashed( $offset, $limit, array $sort = null );

    /**
     * Removes trashed element identified by $id from trash.
     * Will NOT remove associated content object nor attributes.
     *
     * @param int $id The trashed location Id
     *
     * @return void
     */
    abstract public function removeElementFromTrash( $id );

    /**
     * Set section on all content objects in the subtree
     *
     * @param mixed $pathString
     * @param mixed $sectionId
     *
     * @return boolean
     */
    abstract public function setSectionForSubtree( $pathString, $sectionId );

    /**
     * Changes main location of content identified by given $contentId to location identified by given $locationId
     *
     * Updates ezcontentobject_tree table for the given $contentId and eznode_assignment table for the given
     * $contentId, $parentLocationId and $versionNo
     *
     * @param mixed $contentId
     * @param mixed $locationId
     *
     * @return void
     */
    abstract public function changeMainLocation( $contentId, $locationId );
}