<?php

namespace App\Repositories\Story;

use App\Models\Story\DescriptionGrade;
use App\Models\Story\Grade;
use App\Repositories\EloquentRepository;

class GradeRepository extends EloquentRepository
{

    public function getModel()
    {
        return Grade::class;
    }

    public function getDataByLanguageDisplay()
    {
        return $this->_model
            ->select(
                Grade::TABLE . '.' . Grade::_NAME,
                DescriptionGrade::TABLE . '.' . DescriptionGrade::_DES,
                DescriptionGrade::TABLE . '.' . DescriptionGrade::_LANG_DISPLAY,
                Grade::TABLE . '.' . Grade::_GROUP,
                Grade::TABLE . '.' . Grade::_ID
            )
            ->join(DescriptionGrade::TABLE, DescriptionGrade::TABLE . '.' . DescriptionGrade::_GRADE_ID, Grade::TABLE . '.' . Grade::_ID)
            ->get();
    }
}
