@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Добавить тег</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.tags.store') }}">
        @csrf
        <div class="mb-3">
            <label class="mb-2">Тег *</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection