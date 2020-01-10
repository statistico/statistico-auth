<?php

namespace Statistico\Auth\Application\Http\ApiV1;

use Statistico\Auth\Framework\Jsend\JsendError;
use Statistico\Auth\Framework\Jsend\JsendErrorResponse;
use Statistico\Auth\Framework\Jsend\JsendFailResponse;
use Statistico\Auth\Framework\Jsend\JsendSuccessResponse;

trait CreatesJsendResponses
{
    /**
     * @param mixed $data
     * @param int $status
     * @param array|string[] $headers
     * @return JsendSuccessResponse
     */
    public function createSuccessResponse($data, int $status, array $headers = []): JsendSuccessResponse
    {
        return (new JsendSuccessResponse($data, $headers))->setStatusCode($status);
    }

    /**
     * @param array|string[] $errors
     * @param int $status
     * @param array|string[] $headers
     * @return JsendFailResponse
     */
    public function createFailResponse(array $errors, int $status, array $headers = []): JsendFailResponse
    {
        $errors = $this->hydrateErrors($errors);

        return (new JsendFailResponse($errors, $headers))->setStatusCode($status);
    }

    /**
     * @param array|string[] $errors
     * @param int $status
     * @param array|string[] $headers
     * @return JsendErrorResponse
     */
    public function createErrorResponse(array $errors, int $status, array $headers = []): JsendErrorResponse
    {
        $errors = $this->hydrateErrors($errors);

        return (new JsendErrorResponse($errors, $headers))->setStatusCode($status);
    }

    /**
     * @param array|string[] $errors
     * @return array|JsendError[]
     */
    private function hydrateErrors(array $errors): array
    {
        $jsendErrors = [];

        foreach ($errors as $error) {
            $jsendErrors[] = new JsendError($error);
        }

        return $jsendErrors;
    }
}
