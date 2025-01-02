@extends('admin.layout')

@section('content')

    <script>
        function clearImage(name) {
            if (confirm('Вы уверены, что хотите удалить изображение?')) {
                const imgElement = document.getElementById(`${name}_img`);
                if (imgElement) {
                    imgElement.src = '';
                }
                const inputElement = document.getElementById(`${name}_imgsrc`);
                if (inputElement) {
                    inputElement.value = '';
                }
            }
        }
    </script>
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
                <option value="0">Без квеста</option>
                @foreach($quests as $quest => $q_id)
                <option value="{{$q_id}}" {{($q_id == $sight->quest_id) ? ' selected' : ''}}>{{$quest}}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="mb-3">
            <label class="mb-2 w-100">Изображение</label>
            <img src="/img/{{$sight->image}}" alt="" class="mb-2 border border-1" width="500px">
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

        <div class="mb-5">
            <label class="mb-2">Координаты</label>
            <input type="text" class="form-control" name="latitude" value="{{$sight->latitude}}">
            <input type="text" class="form-control" name="longitude" value="{{$sight->longitude}}">
        </div>


        
        <div class="mb-3">
            <label class="mb-2">Вопрос 1 *</label>
            <div class="d-flex">
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3">{{$question1[0]['text']}}</textarea>
                    <p><img src="/img/{{$question1[0]['image']}}" id="task1_1_img" class="mb-2 border border-1" alt="No image" width="300"></p>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                    <input type="hidden" name="task1_id[]" value="{{$question1[0]['id']}}">
                    <input type="hidden" name="task1_imgsrc[]" id="task1_1_imgsrc" value="{{$question1[0]['image']}}">
                    <button type="button" onclick="clearImage('task1_1')">Удалить изображение</button>
                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3">{{$question1[1]['text']}}</textarea>
                    <p><img src="/img/{{$question1[1]['image']}}" id="task1_2_img" class="mb-2 border border-1" alt="No image" width="300"></p>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                    <input type="hidden" name="task1_id[]" value="{{$question1[1]['id']}}">
                    <input type="hidden" name="task1_imgsrc[]" id="task1_2_imgsrc" value="{{$question1[1]['image']}}">
                    <button type="button" onclick="clearImage('task1_2')">Удалить изображение</button>

                </div>
                <div class="mb-2">
                    <textarea class="form-control" name="task1_text[]" rows="3">{{$question1[2]['text']}}</textarea>
                    <p><img src="/img/{{$question1[2]['image']}}" id="task1_3_img" class="mb-2 border border-1" alt="No image" width="300"></p>
                    <input class="form-control" type="file" aria-describedby="questImageHelp" name="task1_image[]">
                    <input type="hidden" name="task1_id[]" value="{{$question1[2]['id']}}">
                    <input type="hidden" name="task1_imgsrc[]" id="task1_3_imgsrc" value="{{$question1[2]['image']}}">            
                    <button type="button" onclick="clearImage('task1_3')">Удалить изображение</button>


                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 1 *</label>
            <input type="text" class="form-control" name="answer1" value="{{$sight->answer1}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 1</label>
            <textarea class="form-control" name="hint1_text" rows="3">{{$hint1[0]['text']}}</textarea>
            <p><img src="/img/{{$hint1[0]['image']}}" id="hint1_img" class="mb-2 border border-1" alt="No image" width="300"></p>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="hint1_image">
            <input type="hidden" name="hint1_id" value="{{$hint1[0]['id']}}">
            <input type="hidden" name="hint1_imgsrc" id="hint1_imgsrc" value="{{$hint1[0]['image']}}">
            <button type="button" onclick="clearImage('hint1')">Удалить изображение</button>
        </div>

        <div class="mb-3">
            <label class="mb-2">Вопрос 2 *</label>
            <textarea class="form-control" name="task2_text" rows="3">{{$question2[0]['text']}}</textarea>
            <p><img src="/img/{{$question2[0]['image']}}" id="task2_img" class="mb-2 border border-1" alt="No image" width="300"></p>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="task2_image">
            <input type="hidden" name="task2_id" value="{{$question2[0]['id']}}">
            <input type="hidden" name="task2_imgsrc" id="task2_imgsrc" value="{{$question2[0]['image']}}">
            <button type="button" onclick="clearImage('task2')">Удалить изображение</button>

        </div>

        <div class="mb-3">
            <label class="mb-2">Ответ 2 *</label>
            <input type="text" class="form-control" name="answer2" value="{{$sight->answer2}}" required>
        </div>

        <div class="mb-3">
            <label class="mb-2">Подсказка 2</label>
            <textarea class="form-control" name="hint2_text" rows="3">{{$hint2[0]['text']}}</textarea>
            <p><img src="/img/{{$hint2[0]['image']}}" id="hint2_img" alt="No image" class="mb-2 border border-1" width="300"></p>
            <input class="form-control" type="file" aria-describedby="questImageHelp" name="hint2_image">
            <input type="hidden" name="hint2_id" value="{{$hint2[0]['id']}}">
            <input type="hidden" name="hint2_imgsrc" id="hint2_imgsrc" value="{{$hint2[0]['image']}}">            
            <button type="button" onclick="clearImage('hint2')">Удалить изображение</button>


        </div>


        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
@endsection