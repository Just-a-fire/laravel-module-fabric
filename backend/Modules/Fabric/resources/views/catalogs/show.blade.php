@extends('fabric::layouts.master')
@section('content')
    <div class="mb-4">
        <a href="{{ route('catalogs.index') }}" class="btn btn-sm btn-link">← Назад</a>
        <h1 class="mt-2">Каталог: {{ $catalog->name }}</h1>
    </div>

    <div class="card">
        <div class="card-header">Ткани в этом каталоге</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Артикул</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($catalog->fabrics as $fabric)
                        <tr>
                            <td>{{ $fabric->article }}</td>
                            <td>{{ $fabric->is_archived ? 'Архив' : 'Актив' }}</td>
                            <td><a href="{{ route('fabrics.show', $fabric) }}" class="btn btn-sm btn-info">Просмотр</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">В этом каталоге пока нет тканей.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
