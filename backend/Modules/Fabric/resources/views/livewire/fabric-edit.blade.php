<div class="card shadow-sm p-4">
    <form wire:submit.prevent="save">
        <div class="row">
            {{-- Артикул --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Артикул</label>
                <input type="text" wire:model.blur="article" class="form-control @error('article') is-invalid @enderror">
                @error('article') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Каталог --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Каталог</label>
                <select wire:model="catalog_id" class="form-select @error('catalog_id') is-invalid @enderror">
                    <option value="">Выберите каталог...</option>
                    @foreach($catalogs as $catalog)
                        <option value="{{ $catalog->id }}">{{ $catalog->name }}</option>
                    @endforeach
                </select>
                @error('catalog_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Статус архивации --}}
        <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" wire:model="is_archived" id="archiveSwitch">
                <label class="form-check-label" for="archiveSwitch">
                    <strong>Ткань в архиве</strong> (не будет отображаться в активных списках)
                </label>
            </div>
        </div>

        {{-- Состав цветов --}}
        <div class="card bg-light mb-3">
            <div class="card-body">
                <h6 class="card-title d-flex justify-content-between">
                    Состав цветов
                    <button type="button" wire:click="addColor" class="btn btn-sm btn-outline-success">+ Добавить цвет</button>
                </h6>
                <hr>

                @foreach($selectedColors as $index => $color)
                    <div class="row g-2 mb-2 align-items-center" wire:key="color-{{ $index }}">
                        <div class="col-md-7">
                            <select wire:model="selectedColors.{{ $index }}.id" class="form-select">
                                <option value="">Выберите цвет...</option>
                                @foreach($allColors as $c)
                                    @php
                                        // Цвет заблокирован, если он уже выбран в другой строке
                                        $isUsed = in_array($c->id, $usedColorIds) && $selectedColors[$index]['id'] != $c->id;
                                    @endphp
                                    <option value="{{ $c->id }}" @if($isUsed) disabled @endif>{{ $c->name }} @if($isUsed) (уже выбран) @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="number" wire:model="selectedColors.{{ $index }}.percentage" class="form-control" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" wire:click="removeColor({{ $index }})" class="btn btn-outline-danger">🗑️</button>
                        </div>
                    </div>
                @endforeach

                @error('selectedColors') <div class="text-danger mt-2 small">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Кнопки действий --}}
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('fabrics.index') }}" class="btn btn-link text-secondary">Отмена</a>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Сохранить изменения</span>
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm" role="status"></span> Сохранение...
                </span>
            </button>
        </div>
    </form>
</div>
