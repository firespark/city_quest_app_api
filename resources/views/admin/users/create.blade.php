@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Добавить пользователя</div>
    </div>

    @include('admin.alerts')

    <form class="w-75" method="post" action="{{ route('admin.users.store') }}" autocomplete="off">
        @csrf
        <div class="mb-3">
            <label class="mb-2">Имя *</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Email *</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Роль *</label>
            <select class="form-select" name="role" required>
                <option value="0" {{ old('role') == 0 ? 'selected' : '' }}>Пользователь</option>
                <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Администратор</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="mb-2">Пароль *</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подтверждение пароля *</label>
            <input type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection