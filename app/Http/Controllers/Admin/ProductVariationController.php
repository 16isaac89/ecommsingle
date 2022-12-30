<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductVariationRequest;
use App\Http\Requests\StoreProductVariationRequest;
use App\Http\Requests\UpdateProductVariationRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Variation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductVariationController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_variation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ProductVariation::with(['product', 'variation'])->select(sprintf('%s.*', (new ProductVariation())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_variation_show';
                $editGate = 'product_variation_edit';
                $deleteGate = 'product_variation_delete';
                $crudRoutePart = 'product-variations';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : '';
            });

            $table->editColumn('product.price', function ($row) {
                return $row->product ? (is_string($row->product) ? $row->product : $row->product->price) : '';
            });
            $table->addColumn('variation_name', function ($row) {
                return $row->variation ? $row->variation->name : '';
            });

            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product', 'variation']);

            return $table->make(true);
        }

        return view('admin.productVariations.index');
    }

    public function create()
    {
        abort_if(Gate::denies('product_variation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variations = Variation::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.productVariations.create', compact('products', 'variations'));
    }

    public function store(StoreProductVariationRequest $request)
    {
        $productVariation = ProductVariation::create($request->all());

        return redirect()->route('admin.product-variations.index');
    }

    public function edit(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $variations = Variation::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $productVariation->load('product', 'variation');

        return view('admin.productVariations.edit', compact('productVariation', 'products', 'variations'));
    }

    public function update(UpdateProductVariationRequest $request, ProductVariation $productVariation)
    {
        $productVariation->update($request->all());

        return redirect()->route('admin.product-variations.index');
    }

    public function show(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariation->load('product', 'variation');

        return view('admin.productVariations.show', compact('productVariation'));
    }

    public function destroy(ProductVariation $productVariation)
    {
        abort_if(Gate::denies('product_variation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productVariation->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductVariationRequest $request)
    {
        ProductVariation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
