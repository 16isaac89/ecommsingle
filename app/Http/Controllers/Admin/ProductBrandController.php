<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProductBrandRequest;
use App\Http\Requests\StoreProductBrandRequest;
use App\Http\Requests\UpdateProductBrandRequest;
use App\Models\ProductBrand;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductBrandController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('product_brand_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productBrands = ProductBrand::all();

        return view('admin.productBrands.index', compact('productBrands'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_brand_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productBrands.create');
    }

    public function store(StoreProductBrandRequest $request)
    {
        $productBrand = ProductBrand::create($request->all());

        return redirect()->route('admin.product-brands.index');
    }

    public function edit(ProductBrand $productBrand)
    {
        abort_if(Gate::denies('product_brand_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productBrands.edit', compact('productBrand'));
    }

    public function update(UpdateProductBrandRequest $request, ProductBrand $productBrand)
    {
        $productBrand->update($request->all());

        return redirect()->route('admin.product-brands.index');
    }

    public function show(ProductBrand $productBrand)
    {
        abort_if(Gate::denies('product_brand_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.productBrands.show', compact('productBrand'));
    }

    public function destroy(ProductBrand $productBrand)
    {
        abort_if(Gate::denies('product_brand_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productBrand->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductBrandRequest $request)
    {
        ProductBrand::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
