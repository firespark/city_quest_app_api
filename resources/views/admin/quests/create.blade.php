@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Добавить квест</div>
    </div>

    @include('admin.errors')
    <form 
        class="w-75" 
        method="post" 
        action="{{ route('admin.quests.store') }}" 
        enctype="multipart/form-data"
    >
        @csrf
        
        <div class="mb-3">
            <label class="mb-2">Заголовок *</label>
            <input type="text" class="form-control" name="title" value="" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Слаг *</label>
            <input type="text" class="form-control" name="slug" value="" required>
        </div>

        <div class="mb-3">
            <label class="mb-2 w-100">Изображение</label>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="image">
            <div id="questImageHelp" class="form-text">Допускаются форматы jpg, jpeg, png, gif, webp</div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Город</label>
            <select class="form-select select2" name="city_id">
                <option value="0" selected>Не опубликовано</option>
                @foreach($cities as $c => $c_id)
                <option value="{{$c_id}}">{{$c}}</option>
                @endforeach
            </select>
        </div>
       
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="questPublished" name="featured">
            <label class="form-check-label" for="questPublished">На главной</label>
        </div>

        <div class="mb-3">
            <label class="mb-2">Описание</label>
            <textarea class="form-control" name="content"></textarea>
        </div>

        <div class="mb-3">
            <label class="mb-2">Финиш</label>
            <textarea class="form-control" name="finish" rows="7"></textarea>
        </div>

        <div class="mb-3">
            <label class="mb-2">Точка начало</label>
            <input type="text" class="form-control" name="start_point" value="">
        </div>

        <div class="mb-3">
            <label class="mb-2">Точка конец</label>
            <input type="text" class="form-control" name="end_point" value="">
        </div>

        <div class="mb-3">
            <label class="mb-2">Количество пропусков</label>
            <input type="number" class="form-control" name="skips_number" value="3">
        </div>

        <div class="mb-3">
            <label class="mb-2">Количество подсказок</label>
            <input type="number" class="form-control" name="hints_number" value="3">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
  
@endsection