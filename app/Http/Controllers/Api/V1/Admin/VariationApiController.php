<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVariationRequest;
use App\Http\Requests\UpdateVariationRequest;
use App\Http\Resources\Admin\VariationResource;
use App\Models\Variation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VariationApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('variation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VariationResource(Variation::all());
    }

    public function store(StoreVariationRequest $request)
    {
        $variation = Variation::create($request->all());

        return (new VariationResource($variation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Variation $variation)
    {
        abort_if(Gate::denies('variation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new VariationResource($variation);
    }

    public function update(UpdateVariationRequest $request, Variation $variation)
    {
        $variation->update($request->all());

        return (new VariationResource($variation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Variation $variation)
    {
        abort_if(Gate::denies('variation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $variation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
