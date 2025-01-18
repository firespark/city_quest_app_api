@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Добавить достопримечательность</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.sights.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="mb-2">Заголовок *</label>
            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Шаг *</label>
            <input type="text" class="form-control" name="step" value="{{ old('step', $step) }}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Квест *</label>
            @if ($quests)
            <select class="form-select form-select select2" name="quest_id" autocomplete="off">
                <option value="0" {{ old('quest_id', $quest_id) == 0 ? 'selected' : '' }}>Без квеста</option>
                @foreach($quests as $quest => $q_id)
                <option value="{{ $q_id }}" {{ old('quest_id', $quest_id) == $q_id ? 'selected' : '' }}>{{ $quest }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="mb-3">
            <label class="mb-2">Изображение</label>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="image">
            <div id="questImageHelp" class="form-text">Допускаются форматы jpg, jpeg, png, gif, webp</div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Описание</label>
            <textarea class="form-control" name="description" rows="7">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="mb-2">Адрес</label>
            <input type="text" class="form-control" name="address" value="{{ old('address') }}">
        </div>

        <div class="mb-5">
            <label class="mb-2">Координаты</label>
            <input type="text" class="form-control" name="latitude" value="{{ old('latitude') }}">
            <input type="text" class="form-control" name="longitude" value="{{ old('longitude') }}">
        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 1 *</label>
            <div class="d-flex">
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3">{{ old('task1_text.0') }}</textarea>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3">{{ old('task1_text.1') }}</textarea>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3">{{ old('task1_text.2') }}</textarea>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 1 *</label>
            <input type="text" class="form-control" name="answer1" value="{{ old('answer1') }}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 1</label>
            <textarea class="form-control" name="hint1_text" rows="3">{{ old('hint1_text') }}</textarea>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="hint1_image">
        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 2 *</label>
            <textarea class="form-control" name="task2_text" rows="3">{{ old('task2_text') }}</textarea>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="task2_image">
        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 2 *</label>
            <input type="text" class="form-control" name="answer2" value="{{ old('answer2') }}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 2</label>
            <textarea class="form-control" name="hint2_text" rows="3">{{ old('hint2_text') }}</textarea>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="hint2_image">
        </div>

        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
@endsection
