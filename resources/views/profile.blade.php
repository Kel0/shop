@extends('layouts.master')

@section('static')
    <link rel="stylesheet" href="{{ asset('css/phorum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        <img src="images/{{ Session::get('image') }}">
    @endif

    @if ($error = Session::get("error"))
        <script>
            alert("{{ $error }}");
        </script>
    @endif

    <div class="wrapper">
        <main>
            <div class="profile">
                <div class="profile-photo" style="background-image: url({{ asset('images/'. $user->photo_name . '') }});"></div>
                <div class="profile-name">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="profile-button">Logout</button>
                    </form>
                    <p style="padding: 10px 15px;">
                      {{ $user->name }} <br> <span style="font-size: 14px;">Будь активен чтобы получать очки!</span>
                    </p>
                    
                    <form action="{{ route('image.upload.post') }}" method="POST" enctype="multipart/form-data"
                        style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 15px; max-width: 300px;">
                        @csrf
                        <input class="profile-button" type="file" name="image" class="form-control">
                        <button class="profile-button" type="submit">
                            Изменить фото
                        </button>
                    </form><br>
                    
                </div>
                <div class="like-dislike">
                    <p class="balance">Баланс: {{ $user->points }} points</p>
                    <p class="like-amount"><span class="iconify" data-icon="bx:bxs-like" data-inline="false"></span>
                        {{ $meta["likes"] }}
                    </p>

                    <p class="dislike-amount">
                        <span class="iconify" data-icon="bx:bxs-dislike" data-inline="false"></span>
                        {{ $meta["dislikes"] }}
                    </p>
                </div>
            </div>
            <div class="written-content">
                <div class="written-content__buttons">
                    <button class="posts">
                        Показать посты (x{{ count($posts) }})
                    </button>
                    <button class="comments">
                        Показать комментарии (x{{ count($comments) }})
                    </button>
                </div>
                <div class="written-content__posts">
                    
                </div>
                <div class="written-content__comments">

                </div>
            </div>
        </main>
    </div>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        window.onload = () => {
            let posts_block = document.querySelector(".written-content__posts"),
                comments_block = document.querySelector(".written-content__comments"),
                posts = {!! $posts_json !!},
                comments = {!! $comments_json !!},
                obj = {
                    "discussion": "Обсуждение",
                    "teammates_search": "Поиск игроков",
                    "error_analytics": "Аналитика ошибок",
                    "team_search": "Ищу команду",
                    "restore_access": "Восстановление доступа к аккаунту",
                    "feedback": "Отзыв"
                };

            posts.forEach(post => {
                posts_block.innerHTML += `
                    <div class="content-commentary__item">
                        <h2 class="post-title">${ post.title } (${ obj[post.category] })</h2>
                        <p class="commentary-content">${ post.content }</p>
                        <div class="like-dislike">
                            <br>
                            <p class="like-amount">
                                <span class="iconify" data-icon="bx:bxs-like" data-inline="false"></span> 
                                ${ post.likes_and_dislikes.likes }
                            </p>

                            <p class="dislike-amount">
                                <span class="iconify" data-icon="bx:bxs-dislike" data-inline="false"></span>
                                ${ post.likes_and_dislikes.dislikes }
                            </p>
                        </div>
                    </div>
                `;
            });

            comments.forEach(comment => {
                comments_block.innerHTML += `
                    <div class="content-commentary__item">
                        <h2 class="post-title">Post title: ${ comment.post.title } (${ obj[comment.post.category] })</h2>
                        <hr><br>
                        <p class="commentary-content">${ comment.content }</p>
                        <div class="like-dislike">
                            <br>
                            <p class="like-amount">
                                <span class="iconify" data-icon="bx:bxs-like" data-inline="false"></span> 
                                ${ comment.likes_and_dislikes.likes }
                            </p>

                            <p class="dislike-amount">
                                <span class="iconify" data-icon="bx:bxs-dislike" data-inline="false"></span>
                                ${ comment.likes_and_dislikes.dislikes }
                            </p>
                        </div>
                    </div>
                `;
            });
        }
    </script>

    <script>    
        $('.posts').click(async (e) => {
            e.preventDefault();
            $(".written-content__posts").slideToggle(400);
        });

        $('.comments').click(async (e) => {
            e.preventDefault();
            $(".written-content__comments").slideToggle(400);
        });
    </script>
@endsection