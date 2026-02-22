<div class="card p-4">
    <form wire:submit.prevent="save">
        {{-- Артикул --}}
        <div class="mb-3">
            <label>Артикул</label>
            <input type="text" wire:model.live="article" class="form-control @error('article') is-invalid @enderror">
            @error('article') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Каталог --}}
        <div class="mb-3">
            <label>Каталог</label>
            <select wire:model.live="catalog_id" class="form-control @error('catalog_id') is-invalid @enderror">
                <option value="">Выберите каталог...</option>
                @foreach($catalogs as $catalog)
                    <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                @endforeach
            </select>
            @error('catalog_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Динамические цвета --}}
        <div class="mb-3">
            <label>Состав цветов (всего 100%)</label>
            @foreach($selectedColors as $index => $color)
                <div class="d-flex mb-2 gap-2 shadow-sm p-2 rounded">
                    <select wire:model.live="selectedColors.{{ $index }}.id" class="form-select">
                        <option value="">Цвет...</option>
                        @foreach($allColors as $c)
                            @php
                                // Цвет заблокирован, если он уже выбран в другой строке
                                $isUsed = in_array($c->id, $usedColorIds) && $selectedColors[$index]['id'] != $c->id;
                            @endphp
                            <option value="{{ $c->id }}" @if($isUsed) disabled @endif>{{ $c->name }} @if($isUsed) (уже выбран) @endif</option>
                        @endforeach
                    </select>
                    
                    <input type="number" wire:model.live="selectedColors.{{ $index }}.percentage" 
                           placeholder="%" class="form-control" style="width: 100px;">
                    
                    <button type="button" wire:click="removeColor({{ $index }})" class="btn btn-danger btn-sm">&times;</button>
                </div>
            @endforeach
            
            <button type="button" wire:click="addColor" class="btn btn-outline-secondary btn-sm">+ Добавить цвет</button>
            
            @error('selectedColors') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить ткань</button>
    </form>
</div>
