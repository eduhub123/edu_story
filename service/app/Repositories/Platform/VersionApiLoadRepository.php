<?php

namespace App\Repositories\Platform;

use App\Models\Platform\VersionApiLoad;
use App\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;

class VersionApiLoadRepository extends EloquentRepository
{
    /**
     * get model
     * @return string
     */
    public function getModel() {
        return VersionApiLoad::class;
    }

    public function getListVersionByParams($params)
    {
        $query = $this->_model->select('*');
        if (isset($params['app_id'])  && $params['app_id']) {
            $query = $query->where(VersionApiLoad::_APP_ID, $params['app_id']);
        }
        if (isset($params['type']) && $params['type']) {
            $query = $query->where(VersionApiLoad::_TYPE, $params['type']);
        }
        if (isset($params['list_type'])  && $params['list_type']) {
            $query = $query->whereIn(VersionApiLoad::_TYPE, $params['list_type']);
        }
        return $query->get();
    }

    public function getRowByCondition($app_id, $type = '') {
        if(!$app_id)
            return false;

        $result = $this->_model
            ->select('*')
            ->where('app_id', $app_id);
        if($type) {
            $result = $result->where('type', $type)
                ->first();
        } else {
            $result = $result->get();
        }

        return $result;
    }

    public function getCatVersion($appId, $type)
    {
        return $this->_model
            ->select('version_number')
            ->where('app_id', $appId)
            ->where('type', $type)
            ->first()->toArray();
    }


    public function getVersion($app_id, $type)
    {
        return $this->_model
            ->select('*')
            ->where(VersionApiLoad::_APP_ID, $app_id)
            ->where(VersionApiLoad::_TYPE, $type)
            ->first();
    }

    public function saveVersion($appId, $type, $version = null, $path = null)
    {
        $versionInfo = $this->getVersion($appId, $type);
        if (!$versionInfo) {
            $versionInfo = new VersionApiLoad();

            $versionInfo[VersionApiLoad::_APP_ID]         = $appId;
            $versionInfo[VersionApiLoad::_TYPE]           = $type;
            $versionInfo[VersionApiLoad::_VERSION_NUMBER] = $version ?? 1;
            if ($path) {
                $versionInfo[VersionApiLoad::_FILE_PATH] = $path;
            }
            $versionInfo[VersionApiLoad::_TIME_CREATED]   = time();
        } else {
            $versionInfo[VersionApiLoad::_VERSION_NUMBER] = $version ?? $versionInfo[VersionApiLoad::_VERSION_NUMBER] + 1;
            if ($path) {
                $versionInfo[VersionApiLoad::_FILE_PATH] = $path;
            }
            $versionInfo[VersionApiLoad::_TIME_UPDATED] = time();
        }
        if ($versionInfo->save()) {
            return $versionInfo->toArray();
        } else {
            return false;
        }
    }
}

?>
