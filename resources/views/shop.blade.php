@extends('layouts.master')

@section('static')
    <link rel="stylesheet" href="{{ asset('css/phorum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="wrapper">
        <main>
            <div class="products">
                
            </div>
        </main>
    </div>
@endsection

@section("scripts")
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@if ($error = Session::get('error'))
    <script>
        alert("{{ $error }}");
    </script>
@endif
<script>
    const render_products = async category => {
        let products_block = document.querySelector(".products");

        products_block.innerHTML = "";
        await $.ajax({
            method: "GET",
            url: "{{ route('get_products') }}",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
        })
        .then(res => {
            let _products = res.data;

            _products.forEach(product => {
                if (product.category == category) {
                    products_block.innerHTML += `
                        <div class="card">
                            <img src="https://gamerzclass.com/wp-content/uploads/2020/03/TWISTZZ_1200x590_font-862x424-1.jpg" alt="">
                            <div class="content">
                                <form method="POST" action="{{ route('purchase_item') }}">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn-add-to-cart" style="border: none;" name="item_id" value="${ product.id }">
                                        <span class="iconify" data-icon="icons8:buy" data-inline="false" data-width="35px" data-height="35px"></span>
                                    </button>
                                </form>
                                <div class="content-title">
                                    ${ product.name }
                                </div>
                                <div class="content-info">
                                    ${ product.desc }
                                </div>
                                <div class="content-price">${ product.price } points</div>
                            </div>
                        </div>
                    `;
                }
            });
        })
        .catch(err => console.error(err));
    }
    render_products("tutorial");

    const purchase_item = async item_id => {
        await $.ajax({
            method: "POST",
            url: "{{ route('purchase_item') }}",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"item_id": item_id}
        })
        .then(res => console.log)
        .catch(err => console.error(err));
    }
</script>

@endsection