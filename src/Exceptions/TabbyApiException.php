<?php

namespace Tabby\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class TabbyApiException extends Exception
{
    /**
     * @var array Holds additional context data for the exception.
     */
    protected array $contextData = [];

    /**
     * TabbyApiException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Map status codes to error messages and create an exception.
     *
     * @param Response $response
     * @return self
     */
    public static function fromResponse(Response $response): self
    {
        // Define error messages for common status codes
        $errorMessages = [
            400 => 'One of the required fields is missing or request is not formatted correctly.',
            401 => 'The required authentication header is missing.',
            403 => 'You tried to perform an action which is forbidden.',
            404 => 'You are using an incorrect ID.',
            409 => 'Refund is unavailable.'
        ];

        // Get the mapped error message or a default one
        $error = $errorMessages[$response->status()]
            ?? "Something bad happened. We're notified.";

        // Append additional error details from the response, if available
        $errorData = $response->json();
        if (!empty($errorData['error'])) {
            $error .= ', ' . $errorData['error'];
        }

        // Log the error for debugging
        Log::error('Tabby API request failed', [
            'status' => $response->status(),
            'message' => $error,
            'response' => $errorData
        ]);

        // Create the exception instance
        $exception = new self($error, $response->status());

        // Set additional context data for logging
        $exception->contextData = [
            'status' => $response->status(),
            'response' => $errorData
        ];

        return $exception;
    }

    /**
     * Returns context data for logging or debugging.
     *
     * @return array
     */
    public function context(): array
    {
        return $this->contextData;
    }

    /**
     * Render the exception for API responses, if desired.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'error' => [
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ]
        ], $this->getCode());
    }
}
