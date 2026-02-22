<div x-data="{ openFormModal: false, openDeleteModal: false, colorId: null, colorName: '' }"
     @open-color-modal.window="openFormModal = true"
     @close-color-modal.window="openFormModal = false">

    <div class="d-flex justify-content-between mb-3">
        <h3>Управление цветами</h3>
        <button wire:click="create" class="btn btn-primary">+ Добавить цвет</button>
    </div>

    @if (session()->has('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>Название</th>
                <th>Использований в тканях</th>
                <th class="text-end">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($colors as $color)
                <tr>
                    <td>{{ $color->name }}</td>
                    <td><span class="badge {{ $color->fabrics_count ? 'bg-info' : 'bg-secondary' }}">{{ $color->fabrics_count }}</span></td>
                    <td class="text-end">
                        <button wire:click="edit({{ $color->id }})" class="btn btn-sm btn-outline-primary">✏️</button>
                        <button @click="openDeleteModal = true; colorId = {{ $color->id }}; colorName = '{{ $color->name }}'"
                                class="btn btn-sm btn-outline-danger" 
                                @if($color->fabrics_count > 0) disabled @endif>🗑️</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Модалка формы создания/редактирования --}}
    <div class="modal" 
         style="display: block; background: rgba(0,0,0,0.5);" 
         :style="openFormModal ? 'display: block !important' : ''"
         :class="{ 'show': openFormModal }"
         x-cloak>
        <div class="modal-dialog">
            <form wire:submit.prevent="save" class="modal-content">
                <div class="modal-header">
                    <h5>{{ $isEditMode ? 'Правка' : 'Новый цвет' }}</h5>
                    <button type="button" class="btn-close" @click="openFormModal = false"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Название</label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Модалка удаления --}}
    <div class="modal fade show" 
         :class="{ 'show': openDeleteModal }" 
         x-transition 
         style="display: block; background: rgba(0,0,0,0.5);" 
         :style="openDeleteModal ? 'display: block !important' : ''"
         x-cloak>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">Удалить цвет <strong x-text="colorName"></strong>?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="openDeleteModal = false">Отмена</button>
                    <button class="btn btn-danger" @click="$wire.delete(colorId); openDeleteModal = false">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</div>
