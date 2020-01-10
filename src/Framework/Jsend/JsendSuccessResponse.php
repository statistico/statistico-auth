<?php

namespace Statistico\Auth\Framework\Jsend;

class JsendSuccessResponse extends JsendResponse
{
    /**
     * JsendSuccessResponse constructor.
     * @param mixed $data
     * @param array|string[] $headers
     * @throws \InvalidArgumentException
     */
    public function __construct($data = null, array $headers = [])
    {
        parent::__construct($data, 'success', $headers);
    }
}
