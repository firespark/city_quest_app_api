<div class="col-sm-3">
    <div class="list-group">
        <a href="{{ route('admin.countries.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
            <i class="fas fa-globe-europe"></i> Страны
        </a>

        <a href="{{ route('admin.cities.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
            <i class="fas fa-city"></i> Города
        </a>

        <a href="{{ route('admin.quests.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('admin.quests.*') ? 'active' : '' }}">
            <i class="fas fa-scroll"></i> Квесты
        </a>

        <a href="{{ route('admin.sights.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('admin.sights.*') ? 'active' : '' }}">
            <i class="fas fa-torii-gate"></i> Достопримечательности
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-user-friends"></i> Пользователи
        </a>

        <a href="{{ route('admin.games.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
            <i class="fas fa-gamepad"></i> Игры
        </a>
    </div>
</div>