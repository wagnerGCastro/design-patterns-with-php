<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpNotFoundException;
use Exception;
use App\Classes\Budget;
use App\Classes\CalculatorOfImposts;
use App\Classes\Imposts\Icms;
use App\Classes\Imposts\Iss;

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

            $icms = $this->calculateOfImpost->calculate($this->budget, new Icms());
            $iss = $this->calculateOfImpost->calculate($this->budget, new Iss());

            $result = [
                "budget_icms" => $icms,
                "budget_iss" =>  $iss,
            ];

            $response->getBody()->write(
                json_encode(
                    $result,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );

            $this->logger->info("Get Impost Controller -> " . json_encode($result));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            $this->logger->error("Get Impost Controller ->" . $e->getMessage());
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}
