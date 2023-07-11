<?php

namespace lav45\settings\storage\vault\dto;

use yii\base\ArrayableTrait;

class ErrorDTO
{
    use ArrayableTrait;

    /** @var string */
    public $type;
    /** @var int */
    public $code;
    /** @var string|array */
    public $message;
    /** @var string|array */
    public $request_url;
    /** @var array */
    public $trace = [];

    /**
     * @return string
     * @throws \JsonException
     */
    public function __toString(): string
    {
        $errors = null;
        if (is_array($this->message)) {
            $errors = $this->message['errors'] ?? [];
        }

        if ($errors) {
            return json_encode($errors);
        }

        return json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}