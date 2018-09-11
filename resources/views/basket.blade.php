@extends('layouts.layout')

@section('title')
    Basket
@endsection

@section('content')
        <h1>Basket Price: ${{ $basketPrice }}</h1>
        <h1>Discount: ${{ $discount }}</h1>
        <form method="POST" action="{{ url('/basket/clear') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button class="btn btn-warning pull-center">
                Remove all items
            </button>
        </form>

        <br />

        <div class="row">
            @foreach($productData as $data)
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="{{ $data['product']->imagePath }}" alt="..." class="img-responsive">
                        <div class="caption">
                            <h3>{{ $data['product']->name }}</h3>
                            <p class="description">{{ $data['product']->description }}</p>
                            <div class="clearfix">
                                <div class="pull-left price">${{ $data['product']->price }}</div>
                                <span class="pull-left amount">||Amount: {{ $data['amount'] }}</span>

                                <form method="POST" action="{{ url('/basket/remove') }}">
                                    <input type="hidden" name="productId" value="{{ $data['product']->id }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-danger pull-right">
                                        Remove
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
@endsection