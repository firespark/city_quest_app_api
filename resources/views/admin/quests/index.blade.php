@extends('admin.layout')

@section('content')

              <div class="mb-4">
                
                @if (isset($city)) 
                <div class="h4">Квесты в городе {{$city->title}}</div>
                @else
                <div class="h4">Квесты</div>
                @endif
              </div>
              <div class="mb-4">
                <a href="{{route('admin.quests.create')}}" class="btn btn-primary">Добавить квест</a>
              </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Изображение</th>
                        <th scope="col">Заголовок</th>
                        <th scope="col">Город</th>
                        <th scope="col">Действие</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($quests as $quest)
                      <tr>
                        <th scope="row">{{$quest->id}}</th>
                        <td>
                          <img src="{{$quest->getImage()}}" alt="" width="150">
                        </td>
                        <td><a href="{{route('admin.sights.quest', ['quest_id' => $quest->id])}}">{{$quest->title}}</a></td>
                        <td>{{$quest->getCityTitle()}}</td>
                        <td>
                          <div class="d-flex">
                            <a href="{{route('admin.quests.edit', $quest->id)}}"><i class="far fa-edit"></i></a>
                          </div>
                            
                         
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                {{ $quests->links('vendor.pagination.default') }}

@endsection