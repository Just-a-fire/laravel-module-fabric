<?php

namespace Modules\Fabric\App\Http\Livewire;

use Livewire\Component;
use Modules\Fabric\Entities\Fabric;
use Livewire\WithPagination;

class FabricList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    // Метод архивации вызывается через Alpine/Livewire без перезагрузки
    public function toggleArchive($id)
    {
        $fabric = Fabric::findOrFail($id);
        $fabric->update(['is_archived' => !$fabric->is_archived]);
    }

    public function render()
    {
        return view('fabric::livewire.fabric-list', [
            'fabrics' => Fabric::where('article', 'like', "%{$this->search}%")
                ->with('catalog')
                ->paginate(10)
        ]);
    }
}
