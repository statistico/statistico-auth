<?php

namespace Statistico\Auth\Framework\Jsend;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsendResponse extends JsonResponse
{
    /**
     * @var string
     */
    private $jsendStatus;
    /**
     * @var object
     */
    private $jsendData;

    /**
     * JsendResponse constructor.
     * @param mixed $data
     * @param string $status
     * @param array|string[] $headers
     * @internal You should use new JsendFailResponse, JsendErrorResponse, or JsendSuccessResponse instead
     * @throws \InvalidArgumentException
     */
    public function __construct($data, string $status = 'success', array $headers = [])
    {
        $this->jsendStatus = $status;
        $this->jsendData = $data;

        $data = (object) [
            'status' => $status,
            'data' => $data
        ];

        switch ($status) {
            case 'success':
                $statusCode = 200;
                break;
            case 'fail':
                $statusCode = 400;
                break;
            case 'error':
                $statusCode = 500;
                break;
            default:
                throw new \InvalidArgumentException("Status '$status' is not a valid Jsend status");
        }

        parent::__construct($data, $statusCode, $headers);
    }

    /**
     * @param mixed $data
     * @param array|string[] $headers
     * @return JsendResponse
     * @throws \InvalidArgumentException
     * @internal
     */
    public static function success($data, array $headers = []): JsendResponse
    {
        return new self($data, 'success', $headers);
    }

    /**
     * @param mixed $data
     * @param array|string[] $headers
     * @return JsendResponse
     * @throws \InvalidArgumentException
     * @internal
     */
    public static function fail($data, array $headers = []): JsendResponse
    {
        return new self($data, 'fail', $headers);
    }

    /**
     * @param mixed $data
     * @param array|string[] $headers
     * @return JsendResponse
     * @throws \InvalidArgumentException
     * @internal
     */
    public static function error($data, array $headers = []): JsendResponse
    {
        return new self($data, 'error', $headers);
    }

    /**
     * @param string $json
     * @return JsendResponse
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $json): JsendResponse
    {
        $decoded = json_decode($json);
        if ($decoded === null) {
            throw new \InvalidArgumentException("JSON string could not be decoded '{$json}'");
        }

        return new JsendResponse($decoded->data, $decoded->status);
    }

    /**
     * @param string $json
     * @return JsendResponse
     * @deprecated Use fromString() instead. This method name was a typo. Supported for backwards compatibility
     */
    public static function fromSting(string $json): JsendResponse
    {
        return self::fromString($json);
    }

    public function getJsendStatus(): string
    {
        return $this->jsendStatus;
    }

    public function isError(): bool
    {
        return $this->getJsendStatus() === 'error';
    }

    public function isFail(): bool
    {
        return $this->getJsendStatus() === 'fail';
    }

    public function isSuccess(): bool
    {
        return $this->getJsendStatus() === 'success';
    }

    /**
     * @return mixed
     */
    public function getJsendData()
    {
        return $this->jsendData;
    }
}
