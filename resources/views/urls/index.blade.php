urls.index view


@extends('layouts.app')

@section('content')
    <h1>Сайты</h1>
    @if ($urls)
        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                </tr>
        </table>
    @endif
@endsection
