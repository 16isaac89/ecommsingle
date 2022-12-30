<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubSubCategoryRequest;
use App\Http\Requests\UpdateSubSubCategoryRequest;
use App\Http\Resources\Admin\SubSubCategoryResource;
use App\Models\SubSubCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubSubCategoryApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sub_sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubSubCategoryResource(SubSubCategory::with(['sub_category'])->get());
    }

    public function store(StoreSubSubCategoryRequest $request)
    {
        $subSubCategory = SubSubCategory::create($request->all());

        return (new SubSubCategoryResource($subSubCategory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SubSubCategory $subSubCategory)
    {
        abort_if(Gate::denies('sub_sub_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SubSubCategoryResource($subSubCategory->load(['sub_category']));
    }

    public function update(UpdateSubSubCategoryRequest $request, SubSubCategory $subSubCategory)
    {
        $subSubCategory->update($request->all());

        return (new SubSubCategoryResource($subSubCategory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SubSubCategory $subSubCategory)
    {
        abort_if(Gate::denies('sub_sub_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subSubCategory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
