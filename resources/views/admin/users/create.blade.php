@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Добавить пользователя</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="mb-3">
            <label class="mb-2">Имя *</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Email *</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Пароль *</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection