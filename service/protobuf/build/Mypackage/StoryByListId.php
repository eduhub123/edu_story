<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protobuf/src/story_lang.proto

namespace Mypackage;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *GetListStoryByListId
 *
 * Generated from protobuf message <code>mypackage.StoryByListId</code>
 */
class StoryByListId extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 slang_id = 1;</code>
     */
    protected $slang_id = 0;
    /**
     * Generated from protobuf field <code>int64 sid = 2;</code>
     */
    protected $sid = 0;
    /**
     * Generated from protobuf field <code>int64 lang_id = 3;</code>
     */
    protected $lang_id = 0;
    /**
     * Generated from protobuf field <code>string name = 4;</code>
     */
    protected $name = '';
    /**
     * Generated from protobuf field <code>string zip_size = 5;</code>
     */
    protected $zip_size = '';
    /**
     * Generated from protobuf field <code>int64 version_story = 6;</code>
     */
    protected $version_story = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $slang_id
     *     @type int|string $sid
     *     @type int|string $lang_id
     *     @type string $name
     *     @type string $zip_size
     *     @type int|string $version_story
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Protobuf\Src\StoryLang::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 slang_id = 1;</code>
     * @return int|string
     */
    public function getSlangId()
    {
        return $this->slang_id;
    }

    /**
     * Generated from protobuf field <code>int64 slang_id = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setSlangId($var)
    {
        GPBUtil::checkInt64($var);
        $this->slang_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 sid = 2;</code>
     * @return int|string
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Generated from protobuf field <code>int64 sid = 2;</code>
     * @param int|string $var
     * @return $this
     */
    public function setSid($var)
    {
        GPBUtil::checkInt64($var);
        $this->sid = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 lang_id = 3;</code>
     * @return int|string
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Generated from protobuf field <code>int64 lang_id = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setLangId($var)
    {
        GPBUtil::checkInt64($var);
        $this->lang_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string name = 4;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generated from protobuf field <code>string name = 4;</code>
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
     * Generated from protobuf field <code>string zip_size = 5;</code>
     * @return string
     */
    public function getZipSize()
    {
        return $this->zip_size;
    }

    /**
     * Generated from protobuf field <code>string zip_size = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setZipSize($var)
    {
        GPBUtil::checkString($var, True);
        $this->zip_size = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 version_story = 6;</code>
     * @return int|string
     */
    public function getVersionStory()
    {
        return $this->version_story;
    }

    /**
     * Generated from protobuf field <code>int64 version_story = 6;</code>
     * @param int|string $var
     * @return $this
     */
    public function setVersionStory($var)
    {
        GPBUtil::checkInt64($var);
        $this->version_story = $var;

        return $this;
    }

}

