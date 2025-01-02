@extends('admin.layout')

@section('content')

              <div class="mb-4">
                <div class="h4">Теги</div>
              </div>
              <div class="mb-4">
                <a href="{{route('admin.tags.create')}}" class="btn btn-primary">Добавить тег</a>
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
                      @foreach($tags as $tag)
                      <tr>
                        <th scope="row">{{$tag->id}}</th>
                        <td>{{$tag->title}}</td>
                        <td>
                          <div class="d-flex">
                            <a href="{{route('admin.tags.edit', $tag->id)}}"><i class="far fa-edit"></i></a>&nbsp;
                            <form method="post" action="{{ route('admin.tags.destroy', $tag->id) }}">
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
                <nav aria-label="Page navigation">
                  <ul class="pagination">
                    <li class="page-item disabled">
                      <span class="page-link"><<</span>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active" aria-current="page">
                      <span class="page-link">2</span>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                      <a class="page-link" href="#">>></a>
                    </li>
                  </ul>
                </nav>

@endsection