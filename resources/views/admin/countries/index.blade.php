@extends('admin.layout')

@section('content')
    <div class="mb-4">
        <div class="h4">Страны</div>
    </div>
    <div class="mb-4">
        <a href="{{route('admin.countries.create')}}" class="btn btn-primary">Добавить страну</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($countries as $country)
                    <tr>
                        <th scope="row">{{$country->id}}</th>
                        <td>{{$country->title}}</td>
                        <td class="text-center">
                            @if($country->published)
                                <i class="fas fa-check text-success"></i>
                            @else
                                <i class="fas fa-minus text-muted"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.countries.edit', $country->id)}}"><i class="far fa-edit"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $countries->links('vendor.pagination.default') }}
@endsection