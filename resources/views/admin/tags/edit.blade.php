@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать тег</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.tags.update', $tag->id) }}" autocomplete="off">
        @csrf
        <input name="_method" type="hidden" value="put">
        <div class="mb-3">
            <label class="mb-2">Тег *</label>
            <input type="text" class="form-control" name="title" value="{{$tag->title}}" required>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection