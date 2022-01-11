<?php


namespace App\Jobs;


use App\Services\Platform\VersionService;

class UpdateRedisVersion extends Job
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    private $appId;

    /**
     * UpdateRedisVersion constructor.
     *
     */
    public function __construct($appId)
    {
        $this->appId = $appId;
    }

    /**
     * Execute the job update redis version
     *
     * @return void
     */
    public function handle(VersionService $versionService)
    {
        $versionService->setDataRedisVersion($this->appId);
    }

    public function fail($exception = null)
    {
        var_dump($exception->getMessage());
    }
}
