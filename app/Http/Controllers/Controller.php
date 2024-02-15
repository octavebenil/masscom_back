<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected int $serverError = 500;

    protected int $success = 200;
    protected int $badRequest = 400;
    protected int $unauthorized = 401;
    protected int $notFound = 404;
    protected int $forbidden = 403;
    protected int $upgradeRequired = 426;

    protected stdClass $response;
    /**
     * @var Application|mixed
     */
    protected mixed $database;
    protected string $tableName;

    public function __construct()
    {
        $this->response = new stdClass();
    }
}
