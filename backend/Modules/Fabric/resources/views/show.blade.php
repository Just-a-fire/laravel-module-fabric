@extends('fabric::layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">Ткань: {{ $fabric->article }}</div>
        <div class="card-body">
            <p><strong>Каталог:</strong> {{ $fabric->catalog->name }}</p>
            <p><strong>Статус:</strong> {{ $fabric->is_archived ? 'В архиве' : 'Активна' }}</p>
            <h6>Цвета:</h6>
            <ul>
                @foreach($fabric->colors as $color)
                    <li>{{ $color->name }} ({{ $color->pivot->percentage }}%)</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
