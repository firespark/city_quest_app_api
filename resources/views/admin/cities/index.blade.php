@extends('admin.layout')

@section('content')

              <div class="mb-4">
                <div class="h4">Города</div>
              </div>
              <div class="mb-4">
                <a href="{{route('admin.cities.create')}}" class="btn btn-primary">Добавить город</a>
              </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Заголовок</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($cities as $city)
                      <tr>
                        <th scope="row">{{$city->id}}</th>
                        <td><a href="{{route('admin.quests.city', ['city_id' => $city->id])}}">{{$city->title}}</a></td>
                        <td>
                          <div class="d-flex">
                            <a href="{{route('admin.cities.edit', $city->id)}}"><i class="far fa-edit"></i></a>&nbsp;
                            <!-- <form method="post" action="{{ route('admin.cities.destroy', $city->id) }}">
                            @csrf
                            <input name="_method" type="hidden" value="delete">

                            <button onclick="return confirm('Вы уверены, что хотите удалить этот элемент?')" class="delete-button" type="submit">
                              <i class="fas fa-times-circle"></i>
                            </button>  
                            </form> --> 
                          </div>
                            
                         
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                {{ $cities->links('vendor.pagination.default') }}

@endsection