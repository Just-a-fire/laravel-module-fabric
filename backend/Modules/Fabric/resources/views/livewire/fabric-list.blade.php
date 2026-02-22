<div x-data="{ confirmArchive: false }">
    <input type="text" wire:model.live="search" placeholder="Поиск по артикулу..." class="form-control mb-3">

    <table class="table">
        <thead>
            <tr>
                <th>Артикул</th>
                <th>Каталог</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fabrics as $fabric)
                <tr wire:key="{{ $fabric->id }}">
                    <td><a href="{{ route('fabrics.show', $fabric) }}">{{ $fabric->article }}</a></td>
                    <td>{{ $fabric->catalog->name }}</td>
                    <td>
                        <span class="badge {{ $fabric->is_archived ? 'bg-secondary' : 'bg-success' }}">
                            {{ $fabric->is_archived ? 'Архив' : 'Актив' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('fabrics.edit', $fabric) }}" class="btn btn-sm btn-outline-secondary">✏️ </a>

                        <button 
                            wire:click="toggleArchive({{ $fabric->id }})" 
                            wire:loading.attr="disabled" 
                            class="btn btn-sm btn-outline-primary"
                        >
                            {{-- Скрываем основной текст при загрузке --}}
                            <span wire:loading.remove wire:target="toggleArchive({{ $fabric->id }})">
                                {{ $fabric->is_archived ? 'Восстановить' : 'В архив' }}
                            </span>

                            {{-- Показываем спиннер только во время запроса --}}
                            <span wire:loading wire:target="toggleArchive({{ $fabric->id }})">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Ждите...
                            </span>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $fabrics->links() }}
</div>
