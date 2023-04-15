<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpNotFoundException;
use Exception;
use App\Classes\Budget;
use App\Classes\CalculatorOfImposts;

final class ImpostController
{
    protected LoggerInterface $logger;
    protected Request $request;
    protected Response $response;
    protected array $args;
    protected Budget $budget;
    protected CalculatorOfImposts $calculateOfImpost;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        try {
            $this->budget = new Budget();
            $this->calculateOfImpost = new CalculatorOfImposts();
            $this->budget->value = 100;

            $budgetIcms = $this->calculateOfImpost->calculate($this->budget, "ICMS");
            $budgetIss = $this->calculateOfImpost->calculate($this->budget, "ISS");

            $result = [
                 "budget_icms" => $budgetIcms,
                "budget_iss" => $budgetIss,
            ];

            $response->getBody()->write(
                json_encode(
                    $result,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );

            $this->logger->info("Get Impost Controller");

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            $this->logger->info("Get Impost Controller" . $e->getMessage());
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}
