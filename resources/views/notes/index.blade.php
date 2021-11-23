@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Text</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Updated at</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($notes as $note)
                            <tr>
                                <th scope="row">{{ $note->id }}</th>
                                <td>{{ $note->title }}</td>
                                <td>{{ $note->text }}</td>
                                <td>{{ $note->created_at }}</td>
                                <td>{{ $note->updated_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
@endsection
