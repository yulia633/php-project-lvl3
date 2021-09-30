@extends('layouts.app')

@section('content')
@include('flash::message')
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
<h2 class="mt-5 mb-3">Проверки</h2>
{{ Form::open(['url' => route('urls.checks.store', [$url->id])]) }}
    {{ Form::submit('Запустить проверку', array('class' => 'btn btn-lg btn-primary mb-3')) }}
{{ Form::close() }}
<table class="table table-bordered table-hover text-nowrap">
    <tr>
        <th>ID</th>
        <th>Код ответа</th>
        <th>h1</th>
        <th>keywords</th>
        <th>description</th>
        <th>Дата создания</th>
    </tr>
    @if ($urlChecks)
        @foreach ($urlChecks as $urlCheck)
            <tr>
                <td>{{ $urlCheck->id }}</td>
                <td>{{ $urlCheck->status_code }}</td>
                <td>{{ $urlCheck->h1 }}</td>
                <td>{{ $urlCheck->keywords }}</td>
                <td>{{ $urlCheck->description }}</td>
                <td>{{ $urlCheck->created_at }}</td>
            </tr>
        @endforeach
    @endif
</table>
    <div class="row">
        <div class="col">
            {{ $urlChecks->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
