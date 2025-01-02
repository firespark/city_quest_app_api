@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать квест</div>
    </div>
    <div class="mb-4">
        <div class="h5"><a href="{{route('admin.quests.city', ['city_id' => $city->id])}}">Квесты в городе {{$city->title}}</a></div>
    </div>
    @include('admin.errors')

    <form 
        class="w-75" 
        method="post" 
        action="{{ route('admin.quests.update', $quest->id) }}" 
        autocomplete="off" 
        enctype="multipart/form-data"
    >
        @csrf
        <input name="_method" type="hidden" value="put">

        <div class="mb-3">
            <label class="mb-2">Заголовок *</label>
            <input type="text" class="form-control" name="title" value="{{$quest->title}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Слаг *</label>
            <input type="text" class="form-control" name="slug" value="{{$quest->slug}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2 w-100">Изображение</label>
            <img src="/img/{{$quest->image}}" alt="" class="mb-2 border border-1" width="500px">
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="image">
            <div id="questImageHelp" class="form-text">Допускаются форматы jpg, jpeg, png, gif, webp</div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Город</label>
            <select class="form-select select2" name="city_id">
                <option value="0">Не опубликовано</option>
                @foreach($cities as $c => $c_id)
                <option value="{{$c_id}}"{{($c_id == $quest->city_id) ? ' selected' : ''}}>{{$c}}</option>
                @endforeach
            </select>
        </div>
       
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="questPublished" name="featured"{{($quest->featured == 1) ? ' checked' : ''}}>
            <label class="form-check-label" for="questPublished">На главной</label>
        </div>

        <div class="mb-3">
            <label class="mb-2">Описание</label>
            <textarea class="form-control" name="content">{{$quest->content}}</textarea>
        </div>

        <div class="mb-3">
            <label class="mb-2">Финиш</label>
            <textarea class="form-control" name="finish" rows="7">{{$quest->finish}}</textarea>
        </div>

        <div class="mb-3">
            <label class="mb-2">Точка начало</label>
            <input type="text" class="form-control" name="start_point" value="{{$quest->start_point}}">
        </div>

        <div class="mb-3">
            <label class="mb-2">Точка конец</label>
            <input type="text" class="form-control" name="end_point" value="{{$quest->end_point}}">
        </div>

        <div class="mb-3">
            <label class="mb-2">Количество пропусков</label>
            <input type="number" class="form-control" name="skips_number" value="{{$quest->skips_number}}">
        </div>

        <div class="mb-3">
            <label class="mb-2">Количество подсказок</label>
            <input type="number" class="form-control" name="hints_number" value="{{$quest->hints_number}}">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection