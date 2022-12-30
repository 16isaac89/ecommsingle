@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.subcategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.subcategories.update", [$subcategory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.subcategory.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $subcategory->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subcategory.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="product_category_id">{{ trans('cruds.subcategory.fields.product_category') }}</label>
                <select class="form-control select2 {{ $errors->has('product_category') ? 'is-invalid' : '' }}" name="product_category_id" id="product_category_id">
                    @foreach($product_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('product_category_id') ? old('product_category_id') : $subcategory->product_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('product_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('product_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subcategory.fields.product_category_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection