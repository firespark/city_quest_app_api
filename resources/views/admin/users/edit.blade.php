@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать пользователя</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.users.update', $user->id) }}" autocomplete="off">
        @csrf
        <input name="_method" type="hidden" value="put">
        <div class="mb-3">
            <label class="mb-2">Имя *</label>
            <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Email *</label>
            <input type="email" class="form-control" name="email" value="{{$user->email}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Пароль</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection