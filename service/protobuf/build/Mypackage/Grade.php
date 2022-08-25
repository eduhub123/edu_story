<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protobuf/src/story_lang.proto

namespace Mypackage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *GetListGrade
 *
 * Generated from protobuf message <code>mypackage.Grade</code>
 */
class Grade extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string name = 1;</code>
     */
    protected $name = '';
    /**
     * Generated from protobuf field <code>string des = 2;</code>
     */
    protected $des = '';
    /**
     * Generated from protobuf field <code>int64 lang_display = 3;</code>
     */
    protected $lang_display = 0;
    /**
     * Generated from protobuf field <code>int64 group = 4;</code>
     */
    protected $group = 0;
    /**
     * Generated from protobuf field <code>int64 id = 5;</code>
     */
    protected $id = 0;
    /**
     * Generated from protobuf field <code>int64 order = 6;</code>
     */
    protected $order = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *     @type string $des
     *     @type int|string $lang_display
     *     @type int|string $group
     *     @type int|string $id
     *     @type int|string $order
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Protobuf\Src\StoryLang::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string des = 2;</code>
     * @return string
     */
    public function getDes()
    {
        return $this->des;
    }

    /**
     * Generated from protobuf field <code>string des = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDes($var)
    {
        GPBUtil::checkString($var, True);
        $this->des = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 lang_display = 3;</code>
     * @return int|string
     */
    public function getLangDisplay()
    {
        return $this->lang_display;
    }

    /**
     * Generated from protobuf field <code>int64 lang_display = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setLangDisplay($var)
    {
        GPBUtil::checkInt64($var);
        $this->lang_display = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 group = 4;</code>
     * @return int|string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Generated from protobuf field <code>int64 group = 4;</code>
     * @param int|string $var
     * @return $this
     */
    public function setGroup($var)
    {
        GPBUtil::checkInt64($var);
        $this->group = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 id = 5;</code>
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>int64 id = 5;</code>
     * @param int|string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkInt64($var);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 order = 6;</code>
     * @return int|string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Generated from protobuf field <code>int64 order = 6;</code>
     * @param int|string $var
     * @return $this
     */
    public function setOrder($var)
    {
        GPBUtil::checkInt64($var);
        $this->order = $var;

        return $this;
    }

}

