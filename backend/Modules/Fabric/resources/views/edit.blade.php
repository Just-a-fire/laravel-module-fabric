@extends('fabric::layouts.master')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('fabrics.index') }}">Ткани</a></li>
                <li class="breadcrumb-item active">Редактирование: {{ $fabric->article }}</li>
            </ol>
        </nav>
        <h1>Правка ткани</h1>
    </div>
    
    <livewire:fabric::fabric-edit :fabric="$fabric" />
@endsection
