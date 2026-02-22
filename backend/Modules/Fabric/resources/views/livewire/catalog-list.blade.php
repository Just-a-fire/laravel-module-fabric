<div x-data="{ 
        openFormModal: false,
        openDeleteModal: false, 
        catalogId: null, 
        catalogName: '' 
    }"
    @open-catalog-modal.window="openFormModal = true"
    @close-catalog-modal.window="openFormModal = false"
>
    {{-- Сообщения об ошибках --}}
    @if (session()->has('error')) 
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    <div class="d-flex justify-content-between mb-3">
        <h3>Управление каталогами</h3>
        <button wire:click="create" class="btn btn-primary">+ Создать каталог</button>
    </div>

    {{-- Таблица каталогов --}}
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Название</th>
                <th>Тканей</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($catalogs as $catalog)
                <tr>
                    <td>{{ $catalog->name }}</td>
                    <td><span class="badge {{ $catalog->fabrics_count ? 'bg-info' : 'bg-secondary' }} text-dark">{{ $catalog->fabrics_count }}</span></td>
                    <td>
                        <button wire:click="edit({{ $catalog->id }})" class="btn btn-sm btn-outline-primary">✏️</button>
                        {{-- Кнопка открытия модалки через Alpine --}}
                        <button 
                            @click="openDeleteModal = true; catalogId = {{ $catalog->id }}; catalogName = '{{ $catalog->name }}'"
                            class="btn btn-sm btn-outline-danger"
                            @if($catalog->fabrics_count > 0) disabled title="Нельзя удалить непустой каталог" @endif>
                            🗑️ Удалить
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Модальное окно (Создание/Редактирование) --}}
    <div class="modal"          
         style="display: block; background: rgba(0,0,0,0.5);" 
         :style="openFormModal ? 'display: block !important' : ''"
         :class="{ 'show': openFormModal }"
         x-cloak>
        <div class="modal-dialog">
            <form wire:submit.prevent="save" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditMode ? 'Редактировать' : 'Новый каталог' }}</h5>
                    <button type="button" class="btn-close" @click="openFormModal = false"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название каталога</label>
                        <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="openFormModal = false">Отмена</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm"></span>
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Модальное окно (Удаление) --}}
    <div class="modal fade show" 
         :class="{ 'show': openDeleteModal }" 
         x-transition 
         style="display: block; background: rgba(0,0,0,0.5);" 
         :style="openDeleteModal ? 'display: block !important' : ''"
         x-cloak>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" @click="openDeleteModal = false"></button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить каталог <strong x-text="catalogName"></strong>?</p>
                    <p class="text-muted small">Это действие невозможно отменить.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="openDeleteModal = false">Отмена</button>
                    
                    {{-- Вызов метода Livewire и закрытие окна --}}
                    <button type="button" class="btn btn-danger" 
                            @click="$wire.delete(catalogId); openDeleteModal = false">
                        <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm"></span>
                        Да, удалить
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
