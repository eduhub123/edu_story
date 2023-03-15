<?php

namespace App\Repositories\Story2;

use App\Models\Globals\ListApp;
use App\Models\Story2\Level;
use App\Models\Story2\StoryLang;
use App\Models\Story2\StoryLangCategory;
use App\Models\Story2\StoryLangLevel;
use App\Repositories\EloquentRepository;

class StoryLangRepository extends EloquentRepository
{

    public function getModel()
    {
        return StoryLang::class;
    }

    public function getLastVersionStory($idApp, $idLanguage)
    {
        $query = $this->_model
            ->where(StoryLang::_ID_LANGUAGES, $idLanguage);

        if ($idApp == ListApp::APP_ID_MS_EN || $idApp == ListApp::APP_ID_TUTORING_NATIVE || $idApp == ListApp::APP_ID_TUTORING_PHI) {
            $query->whereIn(StoryLang::_ID_APP, [ListApp::APP_ID_MS_EN, ListApp::APP_ID_TUTORING_NATIVE, ListApp::APP_ID_TUTORING_PHI]);
        } else {
            $query->where(StoryLang::_ID_APP, $idApp);
        }

        return $query->max(StoryLang::_VERSION_STORY);
    }

    public function getStoriesLang($idApp, $idLanguage, $level, $version, $limit = null, $offset = null)
    {

        $query = $this->_model
            ->select(
                StoryLang::TABLE . '.' . StoryLang::_ID_STORIES,
                StoryLang::TABLE . '.' . StoryLang::_ID_STORY_LANG,
                StoryLang::TABLE . '.' . StoryLang::_ID_LANGUAGES,
                StoryLang::TABLE . '.' . StoryLang::_THUMB,
                StoryLang::TABLE . '.' . StoryLang::_DATA,
                StoryLang::TABLE . '.' . StoryLang::_QUALITY_SCORE,
                StoryLang::TABLE . '.' . StoryLang::_PATH_ZIP_FILE,
                StoryLang::TABLE . '.' . StoryLang::_ZIP_SIZE,
                StoryLang::TABLE . '.' . StoryLang::_LEVEL_SYSTEM,
                StoryLang::TABLE . '.' . StoryLang::_VERSION_STORY,
                StoryLang::TABLE . '.' . StoryLang::_DATE_PUBLISH,
                StoryLang::TABLE . '.' . StoryLang::_STATUS,
                StoryLang::TABLE . '.' . StoryLang::_ID_APP
            )
            ->leftJoin(StoryLangLevel::TABLE, StoryLang::TABLE . '.' . StoryLang::_ID_STORY_LANG, StoryLangLevel::TABLE . '.' . StoryLangLevel::_ID_STORY_LANG)
            ->leftJoin(Level::TABLE, StoryLangLevel::TABLE . '.' . StoryLangLevel::_ID_LEVEL, Level::TABLE . '.' . Level::_ID_LEVEL)
            ->distinct()
            ->where(StoryLang::TABLE . '.' . StoryLang::_VERSION_STORY, '>=', $version)
            ->where(StoryLang::TABLE . '.' . StoryLang::_VERSION_STORY, '>', 0);
        if ($idApp) {
            if ($idApp == ListApp::APP_ID_MS_EN || $idApp == ListApp::APP_ID_TUTORING_NATIVE || $idApp == ListApp::APP_ID_TUTORING_PHI) {
                $query->whereIn(StoryLang::TABLE . '.' . StoryLang::_ID_APP, [ListApp::APP_ID_MS_EN, ListApp::APP_ID_TUTORING_NATIVE, ListApp::APP_ID_TUTORING_PHI]);
            } else {
                $query->where(StoryLang::TABLE . '.' . StoryLang::_ID_APP, $idApp);
            }
        }
        if ($idLanguage) {
            $query->where(StoryLang::TABLE . '.' . StoryLang::_ID_LANGUAGES, $idLanguage);
        }
        if ($level) {
            $query->where(Level::TABLE . '.' . Level::_LEVEL, $level);
        }
        if (isset($offset)) {
            $query->limit($limit)
                ->offset($offset);
        }
        return $storiesLang = $query->get();
    }

    public function getListById($listSlangId, $idApp, $idLanguage)
    {
        $query = $this->_model
            ->select(
                StoryLang::TABLE . '.' . StoryLang::_ID_STORY_LANG,
                StoryLang::TABLE . '.' . StoryLang::_NAME,
                StoryLang::TABLE . '.' . StoryLang::_THUMB,
                StoryLang::TABLE . '.' . StoryLang::_DATA,
                StoryLang::TABLE . '.' . StoryLang::_LEVEL_SYSTEM,
                StoryLang::TABLE . '.' . StoryLang::_VERSION_STORY,
                StoryLang::TABLE . '.' . StoryLang::_DATE_PUBLISH,
                StoryLang::TABLE . '.' . StoryLang::_STATUS
            )->with('story_lang_level.level')
            ->with('story_lang_category')
            ->where(StoryLang::TABLE . '.' . StoryLang::_VERSION_STORY, '>', 0)
            ->whereIn(StoryLang::TABLE . '.' . StoryLang::_ID_STORY_LANG, $listSlangId);
        if ($idApp) {
            if ($idApp == ListApp::APP_ID_MS_EN || $idApp == ListApp::APP_ID_TUTORING_NATIVE || $idApp == ListApp::APP_ID_TUTORING_PHI) {
                $query->whereIn(StoryLang::TABLE . '.' . StoryLang::_ID_APP, [ListApp::APP_ID_MS_EN, ListApp::APP_ID_TUTORING_NATIVE, ListApp::APP_ID_TUTORING_PHI]);
            } else {
                $query->where(StoryLang::TABLE . '.' . StoryLang::_ID_APP, $idApp);
            }
        }
        if ($idLanguage) {
            $query->where(StoryLang::TABLE . '.' . StoryLang::_ID_LANGUAGES, $idLanguage);
        }
        return $query->get();
    }

    public function getListByListId($idStoriesLang)
    {
        return $this->_model
            ->select(
                StoryLang::_ID_STORY_LANG,
                StoryLang::_ID_STORIES,
                StoryLang::_ID_LANGUAGES,
                StoryLang::_NAME,
                StoryLang::_PATH_ZIP_FILE,
                StoryLang::_ZIP_SIZE,
                StoryLang::_VERSION_STORY
            )
            ->whereIn(StoryLang::_ID_STORY_LANG, $idStoriesLang)
            ->get();
    }


    public function getStoryLangByIdStory($params)
    {
        return $this->_model
            ->select(
                StoryLang::_ID_STORY_LANG,
                StoryLang::_ID_STORIES,
                StoryLang::_PATH_ZIP_FILE,
                StoryLang::_VERSION_STORY
            )
            ->where(StoryLang::_ID_STORIES, $params['sid'])
            ->where(StoryLang::_ID_APP, $params['app_id'])
            ->first();
    }
}
