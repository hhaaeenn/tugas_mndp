<!DOCTYPE html>
<html lang="en">
@include('layouts.header')
<body>

    @include('layouts.navbar')

    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col">
                <h3 class="fw-bold">@yield('page-title')</h3>
            </div>
        </div>
        
        @yield('content')
    </div>

    @yield('modal')

    @include('layouts.footer')
