@extends('admin.layout')

@section('content')

              <div class="mb-4">
                <div class="h4">Подписчики</div>
              </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Email</th>
                        <th scope="col">Статус</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($subscribers as $subscriber)
                      <tr>
                        <th scope="row">{{$subscriber->id}}</th>
                        <td>{{$subscriber->email}}</td>
                        <td>{{$subscriber->show_status()}}</td>
                        <td>
                          <div class="d-flex">
                            <form method="post" action="/admin/destroy/{{$subscriber->id}}">
                            @csrf

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