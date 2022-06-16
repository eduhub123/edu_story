<?php

namespace App\Repositories\Story2;

use App\Models\Story2\Level;
use App\Repositories\EloquentRepository;

class LevelRepository extends EloquentRepository
{

    public function getModel()
    {
        return Level::class;
    }

    public function getLevelsByIdApp($idApp)
    {
        $query = $this->_model
            ->select(
                Level::TABLE . '.' . Level::_ID_LEVEL,
                Level::TABLE . '.' . Level::_ID_APP,
                Level::TABLE . '.' . Level::_KEY_NAME,
                Level::TABLE . '.' . Level::_KEY_DESCRIPTION,
                Level::TABLE . '.' . Level::_LEVEL,
                Level::TABLE . '.' . Level::_ID_GRADE
            )
            ->where(Level::TABLE . "." . Level::_STATUS, Level::STATUS_ACTIVE);
        if ($idApp) {
            $query->where(Level::TABLE . "." . Level::_ID_APP, $idApp);
        }
        return $query->get();
    }
}
