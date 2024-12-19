<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * @return mixed
     */
    public function getByWeatherFilter(array $foreacast): Collection
    {
        return Product::byWeatherCompatibility($foreacast['is'])->get();
    }

    /**
     * @return mixed
     */
    public function all(int $perPage): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }
}
