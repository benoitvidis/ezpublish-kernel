<?php
/**
 * File containing the BinaryFile Value class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace ezp\Content\FieldType\BinaryFile;
use ezp\Content\FieldType\Value as ValueInterface,
    ezp\Persistence\Content\FieldValue as PersistenceFieldValue,
    ezp\Io\BinaryFile;

/**
 * Value for TextLine field type
 */
class Value implements ValueInterface
{
    /**
     * BinaryFile object
     *
     * @var \ezp\Io\BinaryFile
     */
    public $file;

    /**
     * Original file name
     *
     * @var string
     */
    public $originalFilename;

    /**
     * @var \ezp\Content\FieldType\BinaryFile\Handler
     */
    protected $handler;

    /**
     * Construct a new Value object.
     * To affect a BinaryFile object to the $file property, use the handler:
     * <code>
     * use \ezp\Content\FieldType\BinaryFile;
     * $binaryValue = new BinaryFile\Value;
     * $binaryValue->file = $binaryValue->getHandler()->createFromLocalPath( '/path/to/local/file.txt' );
     * </code>
     */
    public function __construct()
    {
        $this->handler = new Handler;
    }

    /**
     * @see \ezp\Content\FieldType\Value
     * @return \ezp\Content\FieldType\BinaryFile\Value
     */
    public static function fromString( $stringValue )
    {
        $value = new static();
        $value->file = $value->handler->createFromLocalPath( $stringValue );
        $value->originalFilename = basename( $stringValue );
        return $value;
    }

    /**
     * @see \ezp\Content\FieldType\Value
     */
    public function __toString()
    {
        return $this->file->path;
    }

    /**
     * Returns handler object.
     * Useful manipulate {@link self::$file}
     *
     * @return \ezp\Content\FieldType\BinaryFile\Handler
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
