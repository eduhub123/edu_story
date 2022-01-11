<?php

namespace App\Jobs;


use App\Repositories\EloquentRepository;

class InsertLogMongo extends Job
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    private $data;
    private $eloquentRepository;

    /**
     * InsertLogMongo constructor.
     *
     * @param $data
     * @param EloquentRepository $eloquentRepository
     */
    public function __construct($data, EloquentRepository $eloquentRepository)
    {
        $this->data               = $data;
        $this->eloquentRepository = $eloquentRepository;
    }

    /**
     * Execute the job insert log mongo
     *
     * @return void
     */
    public function handle()
    {
        $this->eloquentRepository->insert($this->data);
    }

    public function fail($exception = null)
    {
        var_dump($exception->getMessage());
    }
}
