<?php

namespace App\Grpc\App;

use App\Models\Language;
use App\Models\Story2\FreeStory;
use App\Repositories\Story2\FreeStoryRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mypackage\FreeServiceInterface;
use Mypackage\GetListFreeRequest;
use Mypackage\GetListFreeResponse;
use Mypackage\ListFree;
use Mypackage\ListFreeWithoutType;
use Spiral\GRPC;

class FreeController implements FreeServiceInterface
{

    public function GetListFree(GRPC\ContextInterface $ctx, GetListFreeRequest $in): GetListFreeResponse
    {

        $idApp = $in->getIdApp();
        $type = $in->getType();
        $time = Carbon::createFromTimestamp(time())->startOfDay()->timestamp;


        $listFreeStory = app()->make(FreeStoryRepository::class)->getFreeStory($idApp, $type, $time)->toArray();

        $data = [];
        foreach ($listFreeStory as $freeStory) {
            $idLanguage = Language::getIdLanguageByIdApp($freeStory[FreeStory::_ID_APP]);
            if ($type) {
                $data[$idLanguage][] = $freeStory[FreeStory::_ID_STORY];
            } else {
                $data[$freeStory[FreeStory::_TYPE]][$idLanguage][] = $freeStory[FreeStory::_ID_STORY];
            }
        }


        $response = new GetListFreeResponse();

        if ($type) {
            $result = [];

            foreach ($data as $key => $item) {
                $listFree = new ListFree();
                $listFree->setIdLanguage($key);
                $listFree->setData($item);

                $result[] = $listFree;
            }

            $response->setDataWithType($result);
        } else {
            $result = [];

            foreach ($data as $key => $item) {
                $listFree = new ListFreeWithoutType();
                $listFree->setType(intval($key));
                $list = [];

                foreach ($item as $key1 => $item1) {
                    $listFreeItem = new ListFree();
                    $listFreeItem->setIdLanguage($key1);
                    $listFreeItem->setData($item1);

                    $list[] = $listFreeItem;
                }
                $listFree->setList($list);

                $result[] = $listFree;
            }
            $response->setDataWithoutType($result);
        }


        return $response;
    }
}