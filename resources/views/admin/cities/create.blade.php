@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Добавить город</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.cities.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="mb-2">Город *</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="mb-3">
            <label class="mb-2">Слаг *</label>
            <input type="text" class="form-control" name="slug" required>
        </div>
        <div class="mb-3">
            <label class="mb-2">Контент *</label>
            <textarea name="content" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="mb-2" for="exampleFormControlFile1">Картинка города</label>
            <input type="file" name="image" class="form-control-file form-control" id="exampleFormControlFile1">
        </div>
        <div class="mb-3 form-group form-check">
            <input type="checkbox" class="form-check-input" name="featured" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">На главной</label>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlSelect1" class="mb-2">Родительский город</label>
            <select name="parent_id" class="form-select" id="exampleFormControlSelect1">
            <option value="0">Нет</option>
            @foreach($cities as $city)
            <option value="{{$city->id}}">{{$city->title}}</option>
            @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection