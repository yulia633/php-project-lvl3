@extends('layouts.app')

@section('content')
@include('flash::message')
@section('content')
<div class="container-md mt-5">
    <h1 class="display-4">Сайт: {{ $url->name }}</h1>
    <table class="table table-bordered">
        <tr>
            <td style="width: 200px">ID</td>
            <th>{{ $url->id }}</th>
        </tr>
        <tr>
            <td>Имя</td>
            <td>{{ $url->name }}</td>
        </tr>
        <tr>
            <td>Дата создания</td>
            <td>{{ $url->created_at }}</td>
        </tr>
        <tr>
            <td>Дата обновления</td>
            <td>{{ $url->updated_at }}</td>
        </tr>
    </table>
</div>
@endsection
