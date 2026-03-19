@extends('admin.layout')

@section('content')
    <div class="mb-4"><div class="h4">Добавить страну</div></div>
    @include('admin.alerts')
    <form class="w-75" method="post" action="{{ route('admin.countries.store') }}">
        @csrf
        <div class="mb-3">
            <label class="mb-2">Название страны *</label>
            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="mb-3">
            <label class="mb-2">Слаг *</label>
            <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="published" id="pub" {{ old('published') ? 'checked' : '' }}>
            <label class="form-check-label" for="pub">Опубликована</label>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection