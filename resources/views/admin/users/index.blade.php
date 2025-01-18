@extends('admin.layout')

@section('content')

              <div class="mb-4">
                <div class="h4">Пользователи</div>
              </div>
              <div class="mb-4">
                <a href="{{route('admin.users.create')}}" class="btn btn-primary">Добавить пользователя</a>
              </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Email</th>
                        <th scope="col">Роль</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $user)
                      <tr>
                        <th scope="row">{{$user->id}}</th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->role}}</td>
                        <td>
                          <div class="d-flex">
                            <a href="{{route('admin.users.edit', $user->id)}}"><i class="far fa-edit"></i></a>&nbsp;
                            <form method="post" action="{{ route('admin.users.destroy', $user->id) }}">
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
                {{ $users->links('vendor.pagination.default') }}

@endsection