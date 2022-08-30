@extends('admin::layout')

@component('admin::components.page.header')
@slot('title', trans('admin::resource.edit', ['resource' => trans('product::suppliers.supplier')]))
@slot('subtitle', $supplier->name)

<li><a href="{{ route('admin.suppliers.index') }}">{{ trans('product::suppliers.suppliers') }}</a></li>
<li class="active">{{ trans('admin::resource.edit', ['resource' => trans('product::suppliers.supplier')]) }}</li>
@endcomponent

@section('content')
<form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="form-horizontal"
    id="supplier-edit-form" novalidate>
    {{ csrf_field() }}
    {{ method_field('put') }}

    <div class="row">
        <div class="col-md-8">
            {{ Form::text('supplier_name', trans('product::attributes.supplier_name'), $errors, $supplier, ['required' => true]) }}
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary" data-loading="">
            {{ trans('product::suppliers.form.update') }}
        </button>
    </div>
</form>
@endsection

@include('product::admin.suppliers.partials.shortcuts')