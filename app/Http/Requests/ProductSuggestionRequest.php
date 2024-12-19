<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ProductSuggestionRequest",
 *     type="object",
 *     title="Product Suggestion Request",
 *     description="Request payload for suggesting products based on weather and date",
 *     required={"weather", "date"},
 *
 *     @OA\Property(
 *         property="weather",
 *         type="object",
 *         description="Weather information",
 *         @OA\Property(
 *             property="city",
 *             type="string",
 *             description="City name",
 *             example="Paris"
 *         )
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         description="Date for which suggestions are requested. Must be today or a future date.",
 *         example="2025-02-20"
 *     )
 * )
 */
class ProductSuggestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'weather.city' => [
                'required',
            ],
            'date' => [
                'required',
                'date',
                'after_or_equal:now',
                'nullable',
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'date' => $this->date ? Carbon::parse($this->date) : now(),
        ]);
    }
}
