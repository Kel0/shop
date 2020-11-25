@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="group">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    const create_post = async () => {
        await $.ajax({
            method: "POST",
            url: "/phorum/post.create",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"content": "Some content"}
        })
        .then(resp => console.log(resp))
        .catch(err => console.error(err));
    }
    
    const create_post_comment = async () => {
        await $.ajax({
            method: "POST",
            url: "/phorum/post.comment.create",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"post_id": 5, "content": "Some content"}
        })
        .then(resp => console.log(resp))
        .catch(err => console.error(err));
    }

    const create_post_like_dislike = async () => {
        await $.ajax({
            method: "POST",
            url: "/phorum/post.like_dislike.create",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"post_id": 5, "action_type": "like"}
        })
        .then(resp => console.log(resp))
        .catch(err => console.error(err));
    }

    const create_post_comments_like_dislike = async () => {
        await $.ajax({
            method: "POST",
            url: "/phorum/post.comment.like_dislike.create",
            headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
            data: {"post_comment_id": 2, "action_type": "like"}
        })
        .then(resp => console.log(resp))
        .catch(err => console.error(err));
    }
</script>
@endsection
