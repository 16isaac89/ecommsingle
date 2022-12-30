<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubcategoryRequest;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Models\ProductCategory;
use App\Models\Subcategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('subcategory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subcategory::with(['product_category'])->select(sprintf('%s.*', (new Subcategory())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'subcategory_show';
                $editGate = 'subcategory_edit';
                $deleteGate = 'subcategory_delete';
                $crudRoutePart = 'subcategories';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->addColumn('product_category_name', function ($row) {
                return $row->product_category ? $row->product_category->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'product_category']);

            return $table->make(true);
        }

        return view('admin.subcategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('subcategory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_categories = ProductCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subcategories.create', compact('product_categories'));
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $subcategory = Subcategory::create($request->all());

        return redirect()->route('admin.subcategories.index');
    }

    public function edit(Subcategory $subcategory)
    {
        abort_if(Gate::denies('subcategory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product_categories = ProductCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subcategory->load('product_category');

        return view('admin.subcategories.edit', compact('product_categories', 'subcategory'));
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        $subcategory->update($request->all());

        return redirect()->route('admin.subcategories.index');
    }

    public function show(Subcategory $subcategory)
    {
        abort_if(Gate::denies('subcategory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subcategory->load('product_category', 'subCategorySubSubCategories');

        return view('admin.subcategories.show', compact('subcategory'));
    }

    public function destroy(Subcategory $subcategory)
    {
        abort_if(Gate::denies('subcategory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subcategory->delete();

        return back();
    }

    public function massDestroy(MassDestroySubcategoryRequest $request)
    {
        Subcategory::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
