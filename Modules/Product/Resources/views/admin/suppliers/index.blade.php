@extends('admin::layout')

@component('admin::components.page.header')
@slot('title', trans('product::suppliers.suppliers'))

<li class="active">{{ trans('product::suppliers.suppliers') }}</li>
@endcomponent

@component('admin::components.page.index_table')
@slot('buttons', ['create'])
@slot('resource', 'suppliers')
@slot('name', trans('product::suppliers.supplier'))

@component('admin::components.table')
@slot('thead')
<tr>
    @include('admin::partials.table.select_all')

    <th>{{ trans('product::suppliers.table.supplier_name') }}</th>

    <th data-sort>{{ trans('admin::admin.table.created') }}</th>
</tr>
@endslot
@endcomponent
@endcomponent

@push('scripts')
<script>
    new DataTable('#suppliers-table .table', {
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                { data: 'id',name: 'id', orderable: true, searchable: true, width: '5%' },
                { data: 'supplier_name', name: 'translations.name', orderable: false, defaultContent: '' },
                { data: 'created', name: 'created_at' },
            ],
        });
</script>
@endpush