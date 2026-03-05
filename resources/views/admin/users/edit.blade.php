@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать пользователя</div>
    </div>

    @include('admin.alerts')

    <form class="w-75" method="post" action="{{ route('admin.users.update', $user->id) }}" autocomplete="off">
        @csrf
        <input name="_method" type="hidden" value="put">
        <div class="mb-3">
            <label class="mb-2 text-muted">Email</label>
            <div class="h5 fw-bold">{{$user->email}}</div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Имя *</label>
            <input type="text" class="form-control" name="name" value="{{$user->name}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Роль *</label>
            <select class="form-select" name="role" required>
                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Пользователь</option>
                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Администратор</option>
            </select>
        </div>

        <div class="mb-3">
            <a href="javascript:void(0)" id="togglePassword" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-key"></i> Задать новый пароль
            </a>
        </div>

        <div id="passwordBlock" style="display: none;" class="p-3 border rounded bg-light mb-3">
            <div class="mb-3">
                <label class="mb-2">Новый пароль</label>
                <input type="password" class="form-control" name="password" autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label class="mb-2">Подтверждение пароля</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
            <small class="text-muted">Оставьте поля пустыми, если передумали менять пароль.</small>
        </div>


        <div class="mb-4">
            <label class="h5 mb-3">Купленные квесты</label>

            <div id="selectedQuests" class="mb-3 d-flex flex-wrap selected-quests"></div>
            
            <div class="mb-3">
                <input type="text" id="questSearch" class="form-control" placeholder="Поиск">
            </div>

            <div class="p-3 border rounded bg-white quests-container" id="questsContainer">
                @foreach($quests as $quest)
                    <div class="mb-2 p-2 border-bottom quest-item">
                        <div class="form-check">
                            <input class="form-check-input quest-checkbox" type="checkbox" name="quests[]" 
                                value="{{ $quest->id }}" id="quest_{{ $quest->id }}"
                                data-title="{{ $quest->title }}"
                                {{ in_array($quest->id, $userQuests) ? 'checked' : '' }}>
                            <label class="form-check-label w-100 cursor-pointer" for="quest_{{ $quest->id }}">
                                <span class="quest-name">{{ $quest->title }}</span>
                                <span class="text-muted quest-city"> — {{ $quest->city->title ?? 'Без города' }}</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>


    <script>
        const selectedContainer = document.getElementById('selectedQuests');
        const checkboxes = document.querySelectorAll('.quest-checkbox');
        const searchInput = document.getElementById('questSearch');

        function updateSelectedBadges() {
            selectedContainer.innerHTML = '';
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const badge = document.createElement('div');
                    badge.className = 'quest-badge';
                    badge.innerHTML = `
                        <span>${cb.getAttribute('data-title')}</span>
                        <span class="remove-quest" data-id="${cb.id}">&times;</span>
                    `;
                    selectedContainer.appendChild(badge);
                }
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedBadges);
        });

        selectedContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-quest')) {
                const cbId = e.target.getAttribute('data-id');
                document.getElementById(cbId).checked = false;
                updateSelectedBadges();
            }
        });

        searchInput.addEventListener('input', function(e) {
            const text = e.target.value.toLowerCase();
            document.querySelectorAll('.quest-item').forEach(item => {
                const content = item.innerText.toLowerCase();
                item.style.display = content.includes(text) ? 'block' : 'none';
            });
        });

        updateSelectedBadges();

        document.getElementById('togglePassword').addEventListener('click', function() {
            const block = document.getElementById('passwordBlock');
            if (block.style.display === 'none') {
                block.style.display = 'block';
                this.innerHTML = '<i class="fas fa-times"></i> Отмена';
                this.classList.replace('btn-outline-secondary', 'btn-outline-danger');
            } else {
                block.style.display = 'none';
                this.innerHTML = '<i class="fas fa-key"></i> Задать новый пароль';
                this.classList.replace('btn-outline-danger', 'btn-outline-secondary');
                block.querySelectorAll('input').forEach(i => i.value = '');
            }
        });
    </script>

@endsection