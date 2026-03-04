@extends('admin.layout')

@section('content')

@php
    $statusTexts = [
        0 => 'Без ответов',
        1 => 'Первый ответ',
        2 => 'Оба ответа',
    ];
    $modeTexts = [
        0 => '0',
        1 => 'Турист',
        2 => 'Местный',
        3 => 'Неравнодушный житель',
        4 => 'Краевед',
    ];
@endphp
              <div class="mb-4">
                <div class="h4">Игры</div>
              </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Квест</th>
                        <th scope="col">Шаг</th>
                        <th scope="col">Статус шага</th>
                        <th scope="col">Скипы</th>
                        <th scope="col">Хинты</th>
                        <th scope="col">Сложность</th>
                        <th scope="col">Начато</th>
                        <th scope="col">Обновлено</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($games as $game)
                      <tr>
                        <th scope="row">{{$game->id}}</th>
                        <td>{{$game->user->id}}. {{$game->user->name}} ({{$game->user->email}})</td>
                        <td>{{$game->quest->title}}</td>
                        <td>{{$game->step}} / {{$game->quest->sights->count()}} {{($game->finished) ? '🏁' : ''}}</td>
                        <td>{{$statusTexts[$game->status]}}</td>
                        <td>{{$game->skips_number}}</td>
                        <td>{{$game->hints_number}}</td>
                        <td>{{$modeTexts[$game->mode_id]}}</td>
                        <td>{{$game->created_at}}</td>
                        <td>{{$game->updated_at}}</td>
                        <td>
<!--                           <div class="d-flex">
                            <form method="post" action="/admin/destroy/{{$game->id}}">
                            @csrf

                            <button onclick="return confirm('Вы уверены, что хотите удалить этот элемент?')" class="delete-button" type="submit">
                              <i class="fas fa-times-circle"></i>
                            </button>
                            </form>
                          </div> -->
                            
                         
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                {{ $games->links('vendor.pagination.default') }}

@endsection