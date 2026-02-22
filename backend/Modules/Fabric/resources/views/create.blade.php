@extends('fabric::layouts.master')
@section('content')
    <div class="mb-4">
        <a href="{{ route('fabrics.index') }}" class="text-decoration-none">← Назад к списку</a>
        <h1 class="mt-2">Новая ткань</h1>
    </div>
    <livewire:fabric::fabric-create />
@endsection
