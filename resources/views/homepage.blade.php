@extends('layouts.layout')

@section('title')
    Homepage
@endsection

@section('content')
        <div class="row">
            @foreach($allProducts as $product)
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="{{ $product->imagePath }}" alt="..." class="img-responsive">
                        <div class="caption">
                            <h3>{{ $product->name }}</h3>
                            <p class="description">{{ $product->description }}</p>
                            <div class="clearfix">
                                <div class="pull-left price">${{ $product->price }}</div>

                                <form method="POST" action="{{ url('/basket/add') }}">
                                    <input type="hidden" name="productId" value="{{ $product->id }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-success pull-right">
                                        Add to Basket
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
@endsection