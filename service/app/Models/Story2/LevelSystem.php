<?php

namespace App\Models\Story2;

class LevelSystem
{
    const LEVEL_SYSTEM_11 = 11;// level 1.1
    const LEVEL_SYSTEM_13 = 13;// level 1.3
    const LEVEL_SYSTEM_21 = 21;// level 2.1
    const LEVEL_SYSTEM_22 = 22;// level 2.2
    const LEVEL_SYSTEM_23 = 23;// level 2.3
    const LEVEL_SYSTEM_24 = 24;// level 2.4
    const LEVEL_SYSTEM_41 = 41;// level 4.1
    const LEVEL_SYSTEM_42 = 42;// level 4.2
    const LEVEL_SYSTEM_43 = 43;// level 4.3
    const LEVEL_SYSTEM_51 = 51;// level 5.1
    const LEVEL_SYSTEM_53 = 53;// level 5.3
    const LEVEL_SYSTEM_61 = 61;// level 6.1
    const LEVEL_SYSTEM_71 = 71;// level 7.1
    const LEVEL_SYSTEM_73 = 73;// level 7.3

    const STATUS_PUBLISH = 2;
    const STATUS_DELETE  = 1;
    const STATUS_NEW     = 0;

    public static function checkStatusLevelSystem($levelSystem, $datePublish, $isInHouse)
    {
        if ($isInHouse) {
            if ($levelSystem >= self::LEVEL_SYSTEM_51 && $levelSystem != self::LEVEL_SYSTEM_53 && $levelSystem != self::LEVEL_SYSTEM_73) {
                return self::STATUS_PUBLISH;
            }
            return self::STATUS_DELETE;
        } else {
            if ($levelSystem == self::LEVEL_SYSTEM_71) {
                return self::STATUS_PUBLISH;
            } elseif ($datePublish > 0) {
                return self::STATUS_DELETE;
            }
        }
        return self::STATUS_NEW;
    }
}
