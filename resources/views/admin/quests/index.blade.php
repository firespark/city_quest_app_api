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
          <th class="text-center align-middle" scope="col">#</th>
          @if(isset($city))
            <th class="text-center align-middle" scope="col">Порядок</th>
          @endif
          <th class="text-center align-middle" scope="col">Изображение</th>
          <th class="align-middle" scope="col">Заголовок</th>
          <th class="text-center align-middle" scope="col">Город</th>
          <th class="text-center align-middle" scope="col">Опубликован</th>
          <th class="text-center align-middle" scope="col">Действие</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quests as $quest)
          <tr class="{{ $quest->paid ? 'table-warning' : '' }}">
            <th class="text-center align-middle" scope="row">{{$quest->id}}</th>
            @if(isset($city))
            <td class="text-center align-middle fw-bold">{{$quest->order_number}}</td>
            @endif
            <td>
              <img src="{{$quest->getImage()}}" alt="" width="150">
            </td>
            <td class="align-middle">
              <a href="{{route('admin.sights.quest', ['quest_id' => $quest->id])}}">{{$quest->title}}</a>
            </td>
            <td class="text-center align-middle">{{$quest->getCityTitle()}}</td>

            <td class="text-center align-middle">
              @if($quest->published)
              <i class="fas fa-check text-success"></i> @else
              <i class="fas fa-minus text-muted"></i> @endif
            </td>

            <td class="text-center align-middle">
              <a href="{{route('admin.quests.edit', $quest->id)}}"><i class="far fa-edit"></i></a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $quests->links('vendor.pagination.default') }}

@endsection