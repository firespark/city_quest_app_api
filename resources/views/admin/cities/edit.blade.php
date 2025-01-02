@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать город</div>
    </div>

    @include('admin.errors')

    <form 
        class="w-75" 
        method="post" 
        action="{{ route('admin.cities.update', $city->id) }}" 
        autocomplete="off"  
        enctype="multipart/form-data"
    >
        @csrf
        <input name="_method" type="hidden" value="put">
        <div class="mb-3">
            <label class="mb-2">Город *</label>
            <input type="text" class="form-control" value="{{$city->title}}" name="title" required>
        </div>
        <div class="mb-3">
            <label class="mb-2">Слаг *</label>
            <input type="text" class="form-control" value="{{$city->slug}}" name="slug" required>
        </div>
        <div class="mb-3">
            <label class="mb-2">Контент *</label>
            <textarea name="content" class="form-control" required>{{$city->content}}</textarea>
        </div>
        <div class="mb-3">
            <label class="mb-2 w-100" for="exampleFormControlFile1">Картинка города</label>
            <img src="/img/{{$city->image}}" alt="" class="mb-2 border border-1" width="500px">
            <input type="file" name="image" class="form-control-file form-control" id="exampleFormControlFile1">
        </div>
        <div class="mb-3 form-group form-check">
            <input 
                type="checkbox" 
                lass="form-check-input" 
                name="featured"
                id="exampleCheck1"
                {{ $city->featured ? 'checked' : null }}
            >
            <label class="form-check-label" for="exampleCheck1">На главной</label>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlSelect1" class="mb-2">Родительский город</label>
            <select name="parent_id" class="form-select select2" id="exampleFormControlSelect1">
            <option value="0">Нет</option>
            @foreach($cities as $c)
            <option 
                value="{{$c->id}}"
                {{ $city->parent_id == $c->id ? 'selected' : null }}
            >{{$c->title}}</option>
            @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection