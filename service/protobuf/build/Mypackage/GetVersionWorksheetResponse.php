<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protobuf/src/worksheet.proto

namespace Mypackage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>mypackage.GetVersionWorksheetResponse</code>
 */
class GetVersionWorksheetResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 data = 1;</code>
     */
    protected $data = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $data
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Protobuf\Src\Worksheet::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 data = 1;</code>
     * @return int|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Generated from protobuf field <code>int64 data = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setData($var)
    {
        GPBUtil::checkInt64($var);
        $this->data = $var;

        return $this;
    }

}

