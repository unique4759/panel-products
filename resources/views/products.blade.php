@extends('main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="products" class="w-100">
                <products :categories="{{ json_encode($categories_list) }}"></products>
            </div>
        </div>
    </div>
@endsection
