<?php

namespace App\Repositories\Story;

use App\Models\Story\DescriptionLevel;
use App\Repositories\EloquentRepository;

class DescriptionLevelRepository extends EloquentRepository
{

    public function getModel()
    {
        return DescriptionLevel::class;
    }

    public function getDescription()
    {
        return $this->_model
            ->select(DescriptionLevel::_DESCRIPTION, DescriptionLevel::_LANG_DISPLAY_ID, DescriptionLevel::_LEVEL_ORDER)
            ->get();
    }
}
