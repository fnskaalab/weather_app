<?php

namespace App\Http\Controllers;

use App\Exceptions\WeatherApiException;
use App\Http\Requests\ProductSuggestionRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use App\Services\WeatherService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected WeatherService $weatherService,
        protected ProductRepository $productRepository
    ) {}

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Retrieve a paginated list of products",
     *     description="Returns a paginated collection of products",
     *     operationId="getProducts",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/ProductResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index()
    {
        $products = $this->productRepository->all(20);

        return ProductResource::collection($products);
    }

    /**
     * @OA\Post(
     *     path="/api/products/weather",
     *     summary="Suggest products based on weather conditions",
     *     description="Suggest products filtered by weather compatibility based on the weather forecast for a given location and date.",
     *     operationId="suggestProductByWeather",
     *     tags={"Products"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/ProductSuggestionRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="products",
     *                     type="array",
     *                     description="List of products matching the weather conditions",
     *
     *                     @OA\Items(ref="#/components/schemas/ProductResource")
     *                 ),
     *
     *                 @OA\Property(
     *                     property="weather",
     *                     type="object",
     *                     description="Weather forecast details",
     *                     @OA\Property(property="city", type="string", example="Paris"),
     *                     @OA\Property(property="is", type="float", description="Temperature in Celsius", example=15.2),
     *                     @OA\Property(property="date", type="string", format="date", example="2025-02-20")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error fetching weather data",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Failed to fetch weather data: Invalid API key.")
     *         )
     *     )
     * )
     */
    public function suggestProductByWeather(ProductSuggestionRequest $request)
    {
        $data = $request->validated();

        try {
            $foreacast = $this->weatherService->process(date: $data['date'], location: $data['weather']['city']);

            $products = $this->productRepository->getByWeatherFilter($foreacast);

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'products' => ProductResource::collection($products),
                    'weather' => $foreacast,
                ],
            ]);
        } catch (WeatherApiException|Exception $exception) {
            return new JsonResponse([
                'message' => 'There is errors on the request',
                'errors' => [$exception->getMessage()],
            ], 422);
        }
    }
}
