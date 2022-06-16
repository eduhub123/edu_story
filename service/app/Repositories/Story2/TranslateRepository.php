<?php

namespace App\Repositories\Story2;

use App\Models\Story2\Translate;
use App\Repositories\EloquentRepository;

class TranslateRepository extends EloquentRepository
{

    public function getModel()
    {
        return Translate::class;
    }

    public function getTranslate($keys, $idLangDisplay = null)
    {
        $query = $this->_model
            ->select(Translate::_KEY, Translate::_VALUE,  Translate::_ID_LANG_DISPLAY)
            ->whereIn(Translate::_KEY, $keys);
        if ($idLangDisplay) {
            $query = $query->where(Translate::_ID_LANG_DISPLAY, $idLangDisplay);
        }
        return $query->get();
    }

}
