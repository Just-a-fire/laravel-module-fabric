<?php

namespace Modules\Fabric\App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Fabric\Entities\Catalog;
use Livewire\WithPagination;

class CatalogList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    // Поля для формы
    public $catalogId = null;
    public $name = '';
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|min:2|max:255',
    ];

    // Открытие формы для создания
    public function create()
    {
        $this->reset(['name', 'catalogId', 'isEditMode']);
        $this->dispatch('open-catalog-modal'); // Сигнал для Alpine
    }

    // Открытие формы для редактирования
    public function edit($id)
    {
        $catalog = Catalog::findOrFail($id);
        $this->catalogId = $catalog->id;
        $this->name = $catalog->name;
        $this->isEditMode = true;
        $this->dispatch('open-catalog-modal');
    }

    // Сохранение (и создание, и обновление)
    public function save()
    {
        $this->validate([
            'name' => "required|min:2|unique:catalogs,name,{$this->catalogId}",
        ]);

        if ($this->isEditMode) {
            Catalog::find($this->catalogId)->update(['name' => $this->name]);
        } else {
            Catalog::create(['name' => $this->name]);
        }

        $this->dispatch('close-catalog-modal');
        $this->reset(['name', 'catalogId', 'isEditMode']);
    }

    // Атрибут On заставляет компонент переотрисовываться при получении события
    #[On('fabric-created')]
    public function refreshCatalogs()
    {
        // Метод может быть пустым
    }

    public function render()
    {
        return view('fabric::livewire.catalog-list', [
            'catalogs' => Catalog::where('name', 'like', "%{$this->search}%")
                ->withCount('fabrics')
                ->paginate(10)
        ]);
    }
}