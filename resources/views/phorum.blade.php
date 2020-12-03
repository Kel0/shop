@extends('layouts.master')

@section('static')
    <link rel="stylesheet" href="{{ asset('css/phorum.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="wrapper">
        <main>
            <div class="ticket">
                <h2 class="ticket-title">
                    Опишите свою проблему,<br />
                    И здесь вы найдете ответ!
                </h2>
                <form class="ticket-form">
                    <input type="text" class="ticket-form__input" id="title_area" placeholder="Название тикета">
                    <select class="ticket-form__select" id="categ_select">
                        <option value="discussion">Обсуждение</option>
                        <option value="teammates_search">Поиск игроков</option>
                        <option value="error_analytics">Аналитика ошибок</option>
                        <option value="team_search">Ищу команду</option>
                        <option value="restore_access">Восстановление доступа к аккаунту</option>
                        <option value="feedback">Отзыв</option>
                    </select>
                    <textarea class="ticket-form__descr" name="" id="content_area" cols="30" rows="2"
                        placeholder="Описание"></textarea>
                    <button type="button" class="ticket-form__submit" onclick="create_post(title_area.value, content_area.value, categ_select.value);">Отправить</button>
                </form>
            </div>
            <div class="sorting">
                <h3 class="sorting-title">
                    Выберите категорию вопросов
                </h3>
                <select class="ticket-form__select" id="categ_post_select" onchange="render_questions(this.value);">
                    <option value="all" selected>Все</option>
                    <option value="discussion">Обсуждение</option>
                    <option value="teammates_search">Поиск игроков</option>
                    <option value="error_analytics">Аналитика ошибок</option>
                    <option value="team_search">Ищу команду</option>
                    <option value="restore_access">Восстановление доступа к аккаунту</option>
                    <option value="feedback">Отзыв</option>
                </select>
            </div>
            <table class="forum">
                <thead id="thead"></thead>
                <tbody id="tbody"></tbody>
            </table>
            <div class="overlay">
                <div class="content" style="overflow-y: auto;">
                    <div id="close" onclick="close_modal();"><span class="iconify" data-icon="ion:close-circle-sharp" data-inline="false" style="color: red;" data-width="30"></span></div>
                    <div class="content-post">
                        <div class="post-header">
                            <h3 class="content-title" id="modal_title">
                                Help jalepo Craft
                            </h3>
                            <div class="like-dislike">
                                <p class="like-amount" onclick="set_meta('like', this);" id="post_likes" name="0">
                                    <i class="fa fa-thumbs-up"></i>
                                    <span id="likes_count">0</span>
                                </p>
                                
                                <p class="dislike-amount" onclick="set_meta('dislike', this);" id="post_dislikes" name="0">
                                    <i class="fa fa-thumbs-down"></i>
                                    <span id="dislikes_count">0</span>
                                </p>
                            </div>
                        </div>
                        <p class="content-descr" id="content_desc">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint incidunt accusamus dolore consectetur eveniet quisquam officiis, libero voluptas! Facere praesentium illum quisquam nam voluptatum sed soluta deserunt molestiae excepturi sunt!
                            Enim dolorem rem debitis consequuntur molestiae nulla dignissimos voluptatum impedit at corrupti saepe, perferendis, neque minus ad cum dicta asperiores culpa quia sit autem sint quas, ex quaerat! Quo, repudiandae?
                            Quod architecto fuga tenetur tempore ad, amet molestias dolorem perspiciatis maiores commodi aliquid qui ullam alias fugit earum, officiis quidem cumque consequatur ex quis rerum? Iste exercitationem rem accusamus facilis.
                        </p>
                    </div>
                    <form class="content-form">
                        <h4 class="content-form__title">Напишите свой комментарий</h4>
                        <textarea id="comment_input" cols="30" rows="10"></textarea>
                        <button type="button" class="content-form__submit" onclick="create_post_comment(comment_input.value, this);">
                            Отправить
                        </button>
                    </form>
                    <div class="content-commentary"></div>
                </div>
            </div>
        </main>
    </div>
@endsection

@section('scripts')
<script>
    const create_post_comment = async (content, element) => {
        let post_id = parseInt(element.getAttribute("id"));

        await $.ajax({
            method: "POST",
            url: "/phorum/post.comment.create",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"post_id": post_id, "content": content}
        })
        .then(resp => render_questions(
            document.querySelector("#categ_post_select").value
        ))
        .then(() => render_post(post_id))
        .catch(err => console.error(err));
        
    }

    const set_meta = async (action, element) => {
        let post_id = parseInt(element.getAttribute("name"));

        await $.ajax({
            method: "POST",
            url: "/phorum/post.like_dislike.update",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"post_id": post_id, "action_type": action}
        })
        .then(resp => {
            let message = resp.meta[0],
                dislikes_block = document.querySelector("#post_dislikes").children[1],
                likes_block = document.querySelector("#post_likes").children[1];

            dislikes_block.innerText = message.dislikes;
            likes_block.innerText = message.likes;
        })
        .catch(err => console.error(err));
    }

    const post_comment_set_meta = async (action, element) => {
        let post_comment_id = parseInt(element.getAttribute("name"));

        await $.ajax({
            method: "POST",
            url: "/phorum/post.comment.like_dislike.update",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"action_type": action, "post_comment_id": post_comment_id}
        })
        .then(resp => {
            let message = resp.meta[0],
                dislikes_block = document.querySelector(`#comment_dislike_${ post_comment_id }`).children[1],
                likes_block = document.querySelector(`#comment_like_${ post_comment_id }`).children[1];

            likes_block.innerText = message.likes;
            dislikes_block.innerText = message.dislikes;
        })
        .catch(err => console.error(err));
    }
</script>
<script>
    var all_posts = 0;

    const create_post = async (title, content, category) => {
        await $.ajax({
            method: "POST",
            url: "/phorum/post.create",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"title": title, "content": content, "category": category}
        })
        .then(resp => console.log(resp))
        .catch(err => console.error(err));

        render_questions(
            document.querySelector("#categ_post_select").value
        );
    }

    const render_questions = async category => {
        let tbody = document.querySelector('#tbody'),
            thead = document.querySelector('#thead');
        
        thead.innerHTML = "";
        thead.innerHTML = `
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>More</th>
            </tr>
        `;
        tbody.innerHTML = "";

        await $.ajax({
            method: "GET",
            url: "/phorum/posts.get",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
        })
        .then(resp => {
            let posts = resp.posts;
            all_posts = posts;

            posts.forEach(post => {
                if (category !== undefined && post.category == category) {
                    tbody.innerHTML += `
                        <tr>
                            <td>${ post.id }</td>
                            <td>${ post.title.slice(0, 30) }...</td>
                            <td>${ post.created_at }</td>
                            <td><a class="table-button" id="${ post.id }" onclick="display_modal(this);">More</a></td>
                        </tr>
                    `;
                } 

                if (category == "all") {
                    tbody.innerHTML += `
                        <tr>
                            <td>${ post.id }</td>
                            <td>${ post.title.slice(0, 30) }...</td>
                            <td>${ post.created_at }</td>
                            <td><a class="table-button" id="${ post.id }" onclick="display_modal(this);">More</a></td>
                        </tr>
                    `;
                }
            });            
        })
        .catch(err => console.error(err));
    }
    
    window.onload = () => {
        render_questions(document.querySelector("#categ_post_select").value);
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    const display_modal = element => {
        document.querySelector(".overlay").classList.add("is-on")
        render_post(element.id);
    }
    const close_modal = () => {
        document.querySelector(".overlay").classList.remove("is-on")
    }

    const render_post = post_id => {
        all_posts.forEach(post => {
            if (post.id == post_id) {
                document.querySelector("#modal_title").innerText = post.title;
                document.querySelector("#content_desc").innerText = post.content;

                document.querySelector("#post_likes").children[1].innerText = post.likes_and_dislikes.likes ;
                document.querySelector("#post_dislikes").children[1].innerText = post.likes_and_dislikes.dislikes;
                
                document.querySelector("#post_likes").setAttribute("name", post.id);
                document.querySelector("#post_dislikes").setAttribute("name", post.id);
                document.querySelector(".content-form__submit").setAttribute("id", post.id);

                let block_content = document.querySelector(".content-commentary");
                block_content.innerHTML = "";

                post.comments.forEach(comment => {
                    let content = `
                        <div class="content-commentary__item">
                            <div class="commentary-person">
                                <div class="commentary-photo" style="background: url({{ asset('images/${ comment.user.photo_name }') }})";>

                                </div>
                                <h5 class="commentary-name">
                                    ${ comment.user.name }
                                </h5>
                            </div>
                            <div class="like-dislike">
                                <p class="like-amount" id="comment_like_${ comment.id }" onclick="post_comment_set_meta('like', this);" name="${ comment.id }">
                                    <i class="fa fa-thumbs-up"></i>
                                    <span id="comment_likes:${ comment.id }">${ comment.likes_and_dislikes.likes }</span>
                                </p>
                                
                                <p class="dislike-amount" id="comment_dislike_${ comment.id }" onclick="post_comment_set_meta('dislike', this);" name="${ comment.id }">
                                    <i class="fa fa-thumbs-down"></i>
                                    <span id="comment_dislikes:${ comment.id }">${ comment.likes_and_dislikes.dislikes }</span>          
                                </p>
                            </div>
                            <p class="commentary-content">${ comment.content }</p>
                        </div> <br>
                    `;
                    block_content.innerHTML += content;
                });
            }
        });
    }
</script>
@endsection
