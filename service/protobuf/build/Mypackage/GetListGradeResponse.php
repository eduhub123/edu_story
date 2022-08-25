<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protobuf/src/story_lang.proto

namespace Mypackage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>mypackage.GetListGradeResponse</code>
 */
class GetListGradeResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .mypackage.Grade data = 1;</code>
     */
    private $data;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Mypackage\Grade[]|\Google\Protobuf\Internal\RepeatedField $data
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Protobuf\Src\StoryLang::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .mypackage.Grade data = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Generated from protobuf field <code>repeated .mypackage.Grade data = 1;</code>
     * @param \Mypackage\Grade[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setData($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Mypackage\Grade::class);
        $this->data = $arr;

        return $this;
    }

}

