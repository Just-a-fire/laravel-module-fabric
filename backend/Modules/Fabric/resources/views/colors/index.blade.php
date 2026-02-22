@extends('fabric::layouts.master')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Цвета</h1>
    </div>
    <div class="card p-3">
        <livewire:fabric::color-list /> 
    </div>
@endsection