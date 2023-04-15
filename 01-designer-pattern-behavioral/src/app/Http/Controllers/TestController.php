<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Exception;

final class TestController
{
    protected LoggerInterface $logger;
    protected Request $request;
    protected Response $response;
    protected array $args;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getTest(Request $request, Response $response, array $args): Response
    {
        try {
            $user = [ 'name' => 'wagner.castro', 'email' => 'wagner.castro@test.com'];
            $response->getBody()->write(
                json_encode(
                    $user,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
            $this->logger->info("Get user.");

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}
