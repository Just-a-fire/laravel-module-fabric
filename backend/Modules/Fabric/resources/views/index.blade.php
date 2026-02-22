@extends('fabric::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Список тканей</h1>
        <a href="{{ route('fabrics.create') }}" class="btn btn-primary">Добавить ткань</a>
    </div>
    <livewire:fabric::fabric-list />
@endsection
