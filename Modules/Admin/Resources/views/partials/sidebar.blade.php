<aside class="main-sidebar" id="main-sidebar">
    <header class="main-header clearfix" id="main-header">
        <a class="logo" href="{{ route('admin.dashboard.index') }}">
            <span class="logo-lg">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i aria-hidden="true" class="fa fa-bars"></i>
        </a>
    </header>

    <section class="sidebar">
        {!! $sidebar !!}
    </section>
</aside>
