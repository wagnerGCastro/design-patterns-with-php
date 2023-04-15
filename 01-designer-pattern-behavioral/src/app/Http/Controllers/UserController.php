<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Exception;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection;

final class UserController
{
    protected LoggerInterface $logger;
    protected Request $request;
    protected Response $response;
    protected array $args;
    protected $db;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->db = Manager::connection();
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        try {
            $users = $this->db->select('select * from users');
            // $users = [ 'name' => 'wagner.castro', 'email' => 'wagner.castro@test.com'];

            $result = [
                "users" => $users,
            ];

            $response->getBody()->write(
                json_encode(
                    $result,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );

            $this->logger->info("Users list all -> " . json_encode($users));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}
