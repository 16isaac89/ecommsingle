@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.variation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.variation.fields.id') }}
                        </th>
                        <td>
                            {{ $variation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.variation.fields.name') }}
                        </th>
                        <td>
                            {{ $variation->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.variations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#variation_product_variations" role="tab" data-toggle="tab">
                {{ trans('cruds.productVariation.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="variation_product_variations">
            @includeIf('admin.variations.relationships.variationProductVariations', ['productVariations' => $variation->variationProductVariations])
        </div>
    </div>
</div>

@endsection