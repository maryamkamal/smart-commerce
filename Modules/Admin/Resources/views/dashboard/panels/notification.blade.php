<div class="dashboard-panel">
    <div class="grid-header">
	@if ($message = Session::get('success'))

<div class="alert alert-success alert-block">

	<button type="button" class="close" data-dismiss="alert">×</button>	

        <strong>{{ $message }}</strong>

</div>

@endif


@if ($message = Session::get('error'))

<div class="alert alert-danger alert-block">

	<button type="button" class="close" data-dismiss="alert">×</button>	

        <strong>{{ $message }}</strong>

</div>

@endif
        <h4><i class="fa fa-bell" aria-hidden="true"></i>{{ trans('admin::dashboard.notification') }}</h4>
    </div>
    <div class="row">
        <div class="col-md-12 search">
            <form action="{{route('sendnotification')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group" >
                    <label for="title" class="col-md-3 control-label text-left">
                        {{ trans('admin::dashboard.title') }} :
                    </label>
                    <div class="col-md-7" style="margin-bottom: 20px">
                        <input class="form-control input active" placeholder="title" type="text" name="title" value="" >
						@if($errors->has('title'))
							<div class="alert alert-danger alert-block">

	                        <button type="button" class="close" data-dismiss="alert">×</button>	

                              <strong>{{ $errors->first('title') }}</strong>

                               </div>
                        
                       @endif
                    </div>
                </div>

                <div class="form-group" >
                    <label for="message" class="col-md-3 control-label text-left">
                        {{ trans('admin::dashboard.message') }} :
                    </label>
                    <div class="col-md-7" style="margin-bottom: 20px">
                        <textarea style="max-width: 100%; resize:auto" name="message" id="message" placeholder="Write your message"  cols="50" rows="4" ></textarea>
						@if($errors->has('message'))
                    <div class="alert alert-danger alert-block">

	                        <button type="button" class="close" data-dismiss="alert">×</button>	

                              <strong>{{ $errors->first('message') }}</strong>

                               </div>
                       @endif
                    </div>
                </div>

				{{--    <div class="form-group" >
                    <label for="message" class="col-md-3 control-label text-left">
                        {{ trans('admin::dashboard.choice_image') }} :
                    </label>
                    <div class="col-md-7" style="margin-bottom: 20px">
                        <input class="input active" placeholder="image" type="file" name="image" value="" >
                    </div>
                </div> --}}

                <div class="form-group" >
                    <div class="col-md-3"></div>
                    <div class="col-md-7">
                        <input class="btn btn-primary input active" type="submit"  value=" Submit " >
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="clearfix"></div>

    <hr>

    <div class="table-responsive anchor-table" style="margin-top: 20px">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ trans('admin::dashboard.table.notification_title') }}</th>
                    <th>{{ trans('admin::dashboard.table.message') }}</th>
						{{--  <th>{{ trans('admin::dashboard.table.image') }}</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $notification)
                <tr id="">
                    <td>{{$notification->title}}</td>
                    <td>{{$notification->message}}</td>
						{{--  <td>
                        <img src="{{env('APP_URL').'/storage/media/'.$notification->image}}" alt="Notification Image" style="width:80px;height:80px;">
						</td> --}}
                </tr>
				{{-- @empty
                <tr>
                    <td class="empty" colspan="5">{{ trans('admin::dashboard.no_data') }}</td>
                </tr>--}}
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- <div class="dashboard-panel">
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
</div> --}}
