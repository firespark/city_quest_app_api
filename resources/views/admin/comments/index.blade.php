@extends('admin.layout')

@section('content')

              <div class="mb-4">
                <div class="h4">Комментарии</div>
              </div>
              <div class="mb-4">
                
              </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Текст комментария</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($comments as $comment)
                      <tr>
                        <th scope="row">{{$comment->id}}</th>
                        <td>{{$comment->text}}</td>
                        <td>
                          <a href="/admin/comments/toggle/{{$comment->id}}">
                          @if ($comment->status == 1)
                          <i class="fas fa-exclamation-circle"></i>&nbsp;
                          @else
                          <i class="far fa-thumbs-up"></i>&nbsp;
                          @endif
                          </a>
                          <a href="#"><i class="fas fa-times-circle"></i></a>
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