<div class="row">
    <div class="col-md-8">
        {{ Form::text('supplier_name', trans('product::attributes.supplier_name'), $errors, $supplier, ['required' => true]) }}
    </div>
</div>