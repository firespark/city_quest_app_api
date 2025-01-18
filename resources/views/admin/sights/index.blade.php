@extends('admin.layout')

@section('content')

              <div class="mb-4">
                @if (isset($quest)) 
                <div class="h4">Достопримечательности квеста<br>{{$quest->title}}</div>
                @else
                <div class="h4">Достопримечательности</div>
                @endif
              </div>
              <div class="mb-4">
                <a href="{{route('admin.sights.create', ['quest_id' => (isset($quest)) ? $quest->id : ''])}}" class="btn btn-primary">Добавить достопримечательность</a>
              </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Изображение</th>
                        <th scope="col">Заголовок</th>
                        <th scope="col">Квест</th>
                        <th scope="col">Шаг</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($sights as $sight)
                      <tr>
                        <th scope="row">{{$sight->id}}</th>
                        <td>
                          <img src="{{$sight->getImage()}}" alt="" width="150">
                        </td>
                        <td>{{$sight->title}}</td>
                        <td>{{$sight->quest->title}}</td>
                        <td>{{$sight->step}}</td>
                        <td>
                          <div class="d-flex">
                            <a href="/admin/sights/{{$sight->id}}/edit"><i class="far fa-edit"></i></a>&nbsp;
                            <form method="post" action="{{ route('admin.sights.destroy', $sight->id) }}">
                            @csrf
                            <input name="_method" type="hidden" value="delete">

                            <button onclick="return confirm('Вы уверены, что хотите удалить этот элемент?')" class="delete-button" type="submit">
                              <i class="fas fa-times-circle"></i>
                            </button>
                            </form>
                          </div>
                            
                         
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                {{ $sights->links('vendor.pagination.default') }}
@endsection