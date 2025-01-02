@extends('admin.layout')

@section('content')

    <div class="mb-4">
        <div class="h4">Редактировать достопримечательность</div>
    </div>

    @include('admin.errors')

    <form class="w-75" method="post" action="{{ route('admin.sights.update', $sight->id) }}" autocomplete="off" enctype="multipart/form-data">
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
            <select class="form-select form-select select2" name="quest_id" autocomplete="off">
                @foreach($quests as $quest => $quest_id)
                <option value="{{$quest_id}}"{{($quest_id == $sight->quest_id) ? ' selected' : ''}}>{{$quest}}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 1 *</label>
            <div class="d-flex">
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3" required>{{$question1[0]['text']}}</textarea>
                    <p><img src="/img/{{$question1[0]['image']}}" class="mb-2 border border-1" alt="No image" width="300"></p>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                    
                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3" required>{{$question1[1]['text']}}</textarea>
                    <p><img src="/img/{{$question1[1]['image']}}" class="mb-2 border border-1" alt="No image" width="300"></p>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                    
                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3" required>{{$question1[2]['text']}}</textarea>
                    <p><img src="/img/{{$question1[2]['image']}}" class="mb-2 border border-1" alt="No image" width="300"></p>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 1 *</label>
            <input type="text" class="form-control" name="answer1" value="{{$sight->answer1}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 1</label>
            <textarea class="form-control" name="hint1_text" rows="3" required>{{$hint1[0]['text']}}</textarea>
            <p><img src="/img/{{$hint1[0]['image']}}" class="mb-2 border border-1" alt="No image" width="300"></p>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="hint1_image">
        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 2 *</label>
            <textarea class="form-control" name="question2_text" rows="3" required>{{$question2[0]['text']}}</textarea>
            <p><img src="/img/{{$question2[0]['image']}}" class="mb-2 border border-1" alt="No image" width="300"></p>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="question2_image">
        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 2 *</label>
            <input type="text" class="form-control" name="answer2" value="{{$sight->answer2}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 2</label>
            <textarea class="form-control" name="hint2_text" rows="3" required>{{$hint2[0]['text']}}</textarea>
            <p><img src="/img/{{$hint2[0]['image']}}" alt="No image" class="mb-2 border border-1" width="300"></p>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="hint2_image">
        </div>

        <div class="mb-3">
            <p><img src="{{$sight->getImage()}}" alt="" class="mb-2 border border-1" width="300"></p>
            <label class="mb-2">Изображение</label>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="image">
            <div id="questImageHelp" class="form-text">Допускаются форматы jpg, jpeg, png, gif, webp</div>
        </div>

        

        <div class="mb-3">
            <label class="mb-2">Описание</label>
            <textarea class="form-control" name="description" rows="7">{{$sight->description}}</textarea>
        </div>
        <div class="mb-3">
            <label class="mb-2">Адрес</label>
            <input type="text" class="form-control" name="address" value="{{$sight->address}}">
        </div>

        <div class="mb-3">
            <label class="mb-2">Координаты</label>
            <input type="text" class="form-control" name="latitude" value="{{$sight->latitude}}">
            <input type="text" class="form-control" name="longitude" value="{{$sight->longitude}}">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection