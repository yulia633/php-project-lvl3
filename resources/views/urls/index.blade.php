@extends('layouts.app')

@section('content')
    <div class="container-md mt-5">
        <h1 class="display-4">Сайты</h1>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Последняя проверка</th>
                <th>Код ответа</th>
            </tr>
            @if ($urls)
                @foreach($urls as $url)
                    <tr>
                        <td style="width: 5%">{{$url->id}}</td>
                        <td>
                            <a href="{{route('urls.show', $url->id)}}">{{ $url->name }}</a>
                        </td>
                        <td>{{ $lastChecks[$url->id]->created_at ?? ''}}</td>
                        <td>{{ $lastChecks[$url->id]->status_code ?? '' }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        {{ $urls->links('pagination::bootstrap-4') }}
    </div>
@endsection
