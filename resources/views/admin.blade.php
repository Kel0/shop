@extends('layouts.master')

@section('static')
    <link rel="stylesheet" href="{{ asset('css/phorum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')

<div class="wrapper">
    <main>
        <div class="admin">
            <table class="forum">
                <thead>
                    <tr>
                        <th>Создать продукт</th>
                        <th>Описание</th>
                        <th>Цена в очках</th>
                        <th>Загрузить файл</th>
                        <th>Отправить продукт</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <form action="{{ route('product.create') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <td><input type="text" placeholder="Название" name="name"></td>
                            <td><textarea name="content" cols="30" rows="1"></textarea></td>
                            <td><input type="number" placeholder="Цена в очках" name="price"></td>
                            <td><input type="file" name="file"></input></td>
                            <td><button type="submit" class="table-button">Submit</button></td>
                        </form>
                    </tr>
                </tbody>
            </table>
            <br>
            <table class="forum">
                <thead>
                    <tr>
                        <th>Продукты</th>
                    </tr>
                    <tr>
                        <th>Номер</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
                        <th>Кнопки</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                        <tr>
                            <form action="{{ route('delete_product') }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $product->id }}">
                                <td>{{ $key }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->desc }}</td>
                                <td>{{ $product->price }}</td>
                                <td>
                                    <button class="crud-buttons" href="#">
                                        <span class="iconify" data-icon="ant-design:delete-filled" data-inline="false" data-width="30px"></span>
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($msg = Session::get("status"))
            <script>
                alert("{{ $msg }}");
            </script>
        @endif
    </main>
</div>

@endsection