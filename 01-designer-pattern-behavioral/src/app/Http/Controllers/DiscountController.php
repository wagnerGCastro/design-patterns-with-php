<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpNotFoundException;
use Exception;
use App\Classes\Budget;
use App\Classes\CalculatorOfDiscount;

final class DiscountController
{
    protected LoggerInterface $logger;
    protected Request $request;
    protected Response $response;
    protected array $args;
    protected Budget $budget;
    protected CalculatorOfDiscount $calculateOfDiscount;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        try {
            $this->budget = new Budget();
            $this->calculateOfDiscount = new CalculatorOfDiscount();
            $this->budget->value = 600;
            $this->budget->quantityOfItems = 6;

            $discount = $this->calculateOfDiscount->discount($this->budget);

            $result = [
                "discount" => $discount,
            ];

            $response->getBody()->write(
                json_encode(
                    $result,
                    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );

            $this->logger->info("Get Discount Controller -> " . json_encode($result));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $e) {
            $this->logger->error("Get Impost Controller ->" . $e->getMessage());
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}
