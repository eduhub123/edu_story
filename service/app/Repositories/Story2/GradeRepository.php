<?php

namespace App\Repositories\Story2;

use App\Models\Story2\Category;
use App\Models\Story2\Grade;
use App\Repositories\EloquentRepository;

class GradeRepository extends EloquentRepository
{

    public function getModel()
    {
        return Grade::class;
    }

    public function getGradesByIdApp($idApp)
    {
        $query = $this->_model
            ->select(
                Grade::TABLE . '.' . Grade::_ID_GRADE,
                Grade::TABLE . '.' . Grade::_ID_APP,
                Grade::TABLE . '.' . Grade::_KEY_NAME,
                Grade::TABLE . '.' . Grade::_KEY_DESCRIPTION,
                Grade::TABLE . '.' . Grade::_GROUP
            )
            ->where(Grade::TABLE . "." . Grade::_STATUS, Grade::STATUS_ACTIVE);
        if ($idApp) {
            $query->where(Grade::TABLE . "." . Grade::_ID_APP, $idApp);
        }
        return $query->get();
    }
}
