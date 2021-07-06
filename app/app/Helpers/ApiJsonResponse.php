<?php


namespace App\Helpers;


use Illuminate\Http\JsonResponse;

/**
 * Class Response
 * @package App\Helpers
 */
class ApiJsonResponse extends \Response
{
    const RESPONSE_SUCCESS = 200;
    const RESPONSE_ERRORS = 204;
    const RESPONSE_NOT_AUTHORIZED = 403;
    const RESPONSE_SERVER_ERROR = 500;
    const RESPONSE_BAD_REQUEST = 400;

    /**
     * @var bool
     */
    protected $success = false;
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var int
     */
    protected $code = 200;

    /**
     * @param $massage
     * @return ApiJsonResponse
     */
    public function addError($massage): ApiJsonResponse
    {
        $this->errors[] = $massage;
        return $this;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): ApiJsonResponse
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param int $code
     * @return ApiJsonResponse
     */
    public function setCode(int $code): ApiJsonResponse
    {
        $this->code = $code;
        return $this;
    }


    public function response(): JsonResponse
    {
        if (empty($this->errors)) {
            $this->success = true;
        } else {
            $this->data = null;
        }

        return self::json([
            'success' => $this->success,
            'data' => $this->data,
            'errors' => $this->errors,
        ], $this->code);
    }

}
