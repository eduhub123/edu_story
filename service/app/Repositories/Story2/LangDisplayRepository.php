<?php

namespace App\Repositories\Story2;

use App\Models\Story2\LangDisplay;
use App\Repositories\EloquentRepository;

class LangDisplayRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return LangDisplay::class;
    }

    public function getLangDisplays()
    {
        return $this->_model
            ->where(LangDisplay::_STATUS, LangDisplay::STATUS_ACTIVE)
            ->get();
    }
}
