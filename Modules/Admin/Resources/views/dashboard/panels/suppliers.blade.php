<div class="dashboard-panel">
    <div class="grid-header">
        <h4><i class="fa fa-shopping-cart" aria-hidden="true"></i>{{ trans('admin::dashboard.suppliers') }}</h4>
    </div>
    <div class="row">
        <div class="col-md-12 search">
            <form action="" method="get">

                <div class="form-group">
                    <label for="day"
                        class="col-md-1 control-label text-left">{{ trans('admin::dashboard.by_day') }}</label>
                    <div class="col-md-4">
                        <input class="form-control datetime-picker flatpickr-input input active" placeholder=""
                            type="text" name="day" value="{{ request()->get('day') ?? '' }}" readonly="readonly">
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_date"
                        class="col-md-1 control-label text-left">{{ trans('admin::dashboard.by_start_date') }}</label>
                    <div class="col-md-4">
                        <input class="form-control datetime-picker flatpickr-input input active" placeholder=""
                            type="text" name="start_date" value="{{ request()->get('start_date') ?? '' }}"
                            readonly="readonly">
                    </div>
                </div>
                <div class="form-group">
                    <label for="end_date"
                        class="col-md-1 control-label text-left">{{ trans('admin::dashboard.by_end_date') }}</label>
                    <div class="col-md-4">
                        <input class="form-control datetime-picker flatpickr-input input active" placeholder=""
                            type="text" name="end_date" value="{{ request()->get('end_date') ?? '' }}"
                            readonly="readonly">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" data-loading="">
                        {{ trans('admin::dashboard.submit') }}
                    </button>
                </div>




            </form>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="table-responsive anchor-table">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ trans('admin::dashboard.table.supplier_name') }}</th>
                    <th>{{ trans('admin::dashboard.table.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($supplierOrders as $key => $value)
                <tr id="supplier-{{ $key }}">
                    <td><a href="{{ route('admin.suppliers.edit',$key) }}">{{ $value['name'] }}</a></td>
                    <td>{{ number_format($value['total'],2) }}</td>
                </tr>
                @empty
                <tr>
                    <td class="empty" colspan="5">{{ trans('admin::dashboard.no_data') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>