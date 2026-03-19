@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать страну</div>
    </div>

    @include('admin.alerts')

    <form
        class="w-75"
        method="post"
        action="{{ route('admin.countries.update', $country->id) }}"
        autocomplete="off"
    >
        @csrf
        <input name="_method" type="hidden" value="put">
        
        <div class="mb-3">
            <label class="mb-2">Страна *</label>
            <input type="text" class="form-control" value="{{$country->title}}" name="title" required>
        </div>
        
        <div class="mb-3">
            <label class="mb-2">Слаг *</label>
            <input type="text" class="form-control" value="{{$country->slug}}" name="slug" required>
        </div>

        <div class="mb-3 form-group form-check">
            <input 
                type="checkbox" 
                class="form-check-input" 
                name="published" 
                id="publishedCheck" 
                {{ $country->published ? 'checked' : '' }}
            >
            <label class="form-check-label" for="publishedCheck">Опубликована</label>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        
    </form>
@endsection