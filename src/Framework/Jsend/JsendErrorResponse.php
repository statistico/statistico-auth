<?php

namespace Statistico\Auth\Framework\Jsend;

class JsendErrorResponse extends JsendResponse
{
    /**
     * JsendErrorResponse constructor.
     * @param array|JsendError[] $errors
     * @param array|string[] $headers
     */
    public function __construct(array $errors = [], array $headers = [])
    {
        self::validateErrors($errors);

        parent::__construct(['errors' => $errors], 'error', $headers);
    }

    /**
     * @param array|JsendError[] $errors
     */
    private static function validateErrors(array $errors): void
    {
        foreach ($errors as $error) {
            if (!$error instanceof JsendError) {
                throw new \InvalidArgumentException(
                    'First argument passed to JsendFailResponse::__construct must be an array of JsendError objects'
                );
            }
        }
    }
}
