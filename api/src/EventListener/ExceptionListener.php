<?php

namespace App\EventListener;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ExceptionListener
{
    public function __construct(
        #[Autowire(env: 'APP_ENV')]
        private string $env
    ) {}

    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $previous = $exception->getPrevious();

        if ($exception instanceof HttpException) {
            if ($previous instanceof ValidationFailedException) {
                $event->setResponse($this->createValidationFailedResponse($previous));

                return;
            }

            $event->setResponse($this->createErrorResponse($exception->getStatusCode(), $exception->getMessage()));

            return;
        }

        if ($exception instanceof InvalidArgumentException) {
            $event->setResponse($this->createErrorResponse(Response::HTTP_BAD_REQUEST, $exception->getMessage()));

            return;
        }

        if ('dev' !== $this->env) {
            $event->setResponse($this->createErrorResponse(500));
        }
    }

    private function getHttpMessage(string $message, int $statusCode): string
    {
        if (!empty($message)) {
            return $message;
        }

        return match ($statusCode) {
            404 => 'Page not found',
            default => 'Internal server error'
        };
    }

    private function createErrorResponse(int $statusCode, string $message = ''): JsonResponse
    {
        return new JsonResponse(['error' => ['message' => $this->getHttpMessage($message, $statusCode)]], $statusCode);
    }

    private function createValidationFailedResponse(ValidationFailedException $exception): JsonResponse
    {
        $errors = [];
        $violations = $exception->getViolations();

        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        foreach ($errors as $property => $messages) {
            $errors[$property] = array_unique($messages);
        }

        return new JsonResponse(['error' => ['properties' => $errors]], 422);
    }
}
