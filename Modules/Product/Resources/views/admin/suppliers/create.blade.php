@extends('admin::layout')

@component('admin::components.page.header')
@slot('title', trans('admin::resource.create', ['resource' => trans('product::suppliers.supplier')]))

<li><a href="{{ route('admin.brands.index') }}">{{ trans('product::suppliers.suppliers') }}</a></li>
<li class="active">{{ trans('admin::resource.create', ['resource' => trans('product::suppliers.supplier')]) }}</li>
@endcomponent

@section('content')
<form method="POST" action="{{ route('admin.suppliers.store') }}" class="form-horizontal" id="brand-create-form"
    novalidate>
    {{ csrf_field() }}

    <div class="row">
        <div class="col-md-8">
            {{ Form::text('supplier_name', trans('product::attributes.supplier_name'), $errors, $supplier, ['required' => true]) }}
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary" data-loading="">
            {{ trans('product::suppliers.form.save') }}
        </button>
    </div>
</form>
@endsection

@include('product::admin.suppliers.partials.shortcuts')