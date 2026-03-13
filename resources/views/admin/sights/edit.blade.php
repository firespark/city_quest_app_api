@extends('admin.layout')

@section('content')


    <div class="mb-4">
        <div class="h4">Редактировать достопримечательность</div>
    </div>

    @include('admin.alerts')

    <form class="w-75" method="post" action="{{ route('admin.sights.update', $sight->id) }}" autocomplete="off">
        @csrf
        <input name="_method" type="hidden" value="put">

        <div class="mb-3">
            <label class="mb-2">Заголовок *</label>
            <input type="text" class="form-control" name="title" value="{{$sight->title}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Шаг *</label>
            <input type="text" class="form-control" name="step" value="{{$sight->step}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Квест *</label>
            @if ($quests)
                <select class="form-select select2" name="quest_id" autocomplete="off">
                    <option value="0">Без квеста</option>
                    @foreach($quests as $quest => $q_id)
                        <option value="{{$q_id}}" {{($q_id == $sight->quest_id) ? ' selected' : ''}}>{{$quest}}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="mb-3">
            <label class="mb-2 w-100">Изображение</label>
            @if($sight->image)
                <img src="/img/{{ $sight->image }}" id="main_img"
                    alt="" class="mb-2 border border-1" width="500px">
            @endif
            <input class="form-control" type="text" name="image" id="main_input" value="{{$sight->image}}"
                placeholder="Путь к изображению">

        </div>

        <div class="mb-3">
            <label class="mb-2">Описание</label>
            <textarea class="form-control" name="description" rows="7">{{$sight->description}}</textarea>
        </div>
        <div class="mb-3">
            <label class="mb-2">Адрес</label>
            <input type="text" class="form-control" name="address" value="{{$sight->address}}">
        </div>

        <div class="mb-5">
            <label class="mb-2">Координаты</label>
            <input type="text" class="form-control" name="latitude" value="{{$sight->latitude}}">
            <input type="text" class="form-control" name="longitude" value="{{$sight->longitude}}">
        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 1 *</label>
            <div class="d-flex gap-3">
                <div class="mb-2 w-100">
                    <textarea class="form-control mb-2" name="task1_text[]" rows="3">{{$question1[0]['text']}}</textarea>

                    <input class="form-control mb-2" type="text" id="task1_1_input" name="task1_image[]"
                        value="{{$question1[0]['image']}}" placeholder="Ссылка на изображение">
                    <input type="hidden" name="task1_id[]" value="{{$question1[0]['id']}}">

                    <p><img src="{{ str_starts_with($question1[0]['image'] ?? '', 'http') ? $question1[0]['image'] : '/img/' . $question1[0]['image'] }}"
                            id="task1_1_img" class="mb-2 border border-1" alt="No image" width="300"></p>

                </div>
                <div class="mb-2 w-100">
                    <textarea class="form-control mb-2" name="task1_text[]" rows="3">{{$question1[1]['text']}}</textarea>
                    
                    <input class="form-control mb-2" type="text" id="task1_2_input" name="task1_image[]"
                        value="{{$question1[1]['image']}}" placeholder="Ссылка на изображение">
                    <input type="hidden" name="task1_id[]" value="{{$question1[1]['id']}}">

                    <p><img src="{{ str_starts_with($question1[1]['image'] ?? '', 'http') ? $question1[1]['image'] : '/img/' . $question1[1]['image'] }}"
                            id="task1_2_img" class="mb-2 border border-1" alt="No image" width="300"></p>

                </div>
                <div class="mb-2 w-100">
                    <textarea class="form-control mb-2" name="task1_text[]" rows="3">{{$question1[2]['text']}}</textarea>
                    
                    <input class="form-control mb-2" type="text" id="task1_3_input" name="task1_image[]"
                        value="{{$question1[2]['image']}}" placeholder="Ссылка на изображение">
                    <input type="hidden" name="task1_id[]" value="{{$question1[2]['id']}}">

                    <p><img src="{{ str_starts_with($question1[2]['image'] ?? '', 'http') ? $question1[2]['image'] : '/img/' . $question1[2]['image'] }}"
                            id="task1_3_img" class="mb-2 border border-1" alt="No image" width="300"></p>

                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 1 *</label>
            <input type="text" class="form-control" name="answer1" value="{{$sight->answer1}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 1</label>
            <textarea class="form-control mb-2" name="hint1_text" rows="3">{{$hint1[0]['text']}}</textarea>
            <p><img src="{{ str_starts_with($hint1[0]['image'] ?? '', 'http') ? $hint1[0]['image'] : '/img/' . $hint1[0]['image'] }}"
                    id="hint1_img" class="mb-2 border border-1" alt="No image" width="300"></p>
            <input class="form-control mb-2" type="text" id="hint1_input" name="hint1_image" value="{{$hint1[0]['image']}}"
                placeholder="Ссылка на изображение">
            <input type="hidden" name="hint1_id" value="{{$hint1[0]['id']}}">

        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 2 *</label>
            <textarea class="form-control mb-2" name="task2_text" rows="3">{{$question2[0]['text']}}</textarea>
            <p><img src="{{ str_starts_with($question2[0]['image'] ?? '', 'http') ? $question2[0]['image'] : '/img/' . $question2[0]['image'] }}"
                    id="task2_img" class="mb-2 border border-1" alt="No image" width="300"></p>
            <input class="form-control mb-2" type="text" id="task2_input" name="task2_image"
                value="{{$question2[0]['image']}}" placeholder="Ссылка на изображение">
            <input type="hidden" name="task2_id" value="{{$question2[0]['id']}}">

        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 2 *</label>
            <input type="text" class="form-control" name="answer2" value="{{$sight->answer2}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 2</label>
            <textarea class="form-control mb-2" name="hint2_text" rows="3">{{$hint2[0]['text']}}</textarea>
            <p><img src="{{ str_starts_with($hint2[0]['image'] ?? '', 'http') ? $hint2[0]['image'] : '/img/' . $hint2[0]['image'] }}"
                    id="hint2_img" alt="No image" class="mb-2 border border-1" width="300"></p>
            <input class="form-control mb-2" type="text" id="hint2_input" name="hint2_image" value="{{$hint2[0]['image']}}"
                placeholder="Ссылка на изображение">
            <input type="hidden" name="hint2_id" value="{{$hint2[0]['id']}}">

        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection