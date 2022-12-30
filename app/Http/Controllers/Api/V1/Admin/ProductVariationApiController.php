<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductVariationRequest;
use App\Http\Requests\UpdateProductVariationRequest;
use App\Http\Resources\Admin\ProductVariationResource;
use App\Models\ProductVariation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductVariationApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('product_variation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductVariationResource(ProductVariation::with(['product', 'variation'])->get());
    }

    public function store(StoreProductVariationRequest $request)
    {
        $productVariation = ProductVariation::create($request->all());

        return (new ProductVariationResource($productVariation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductVariationResource($productVariation->load(['product', 'variation']));
    }

    public function update(UpdateProductVariationRequest $request, ProductVariation $productVariation)
    {
        $productVariation->update($request->all());

        return (new ProductVariationResource($productVariation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
