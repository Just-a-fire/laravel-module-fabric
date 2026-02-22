<?php

namespace Modules\Fabric\App\Http\Livewire;

use Livewire\Component;
use Modules\Fabric\Entities\Fabric;
use Modules\Fabric\Entities\Catalog;
use Modules\Fabric\Entities\Color;
use Illuminate\Support\Facades\DB;
use Modules\Fabric\App\Rules\CheckColorPercentage;

class FabricEdit extends Component
{
    public Fabric $fabric; // Livewire автоматически подтянет модель из ID в URL
    public $article;
    public $catalog_id;
    public $is_archived;
    public $selectedColors = [];

    public function mount(Fabric $fabric)
    {
        $this->fabric = $fabric;
        $this->article = $fabric->article;
        $this->catalog_id = $fabric->catalog_id;
        $this->is_archived = $fabric->is_archived;

        // Подгружаем цвета из pivot-таблицы в массив формы
        foreach ($fabric->colors as $color) {
            $this->selectedColors[] = [
                'id' => $color->id,
                'percentage' => $color->pivot->percentage
            ];
        }
    }

    public function addColor()
    {
        $this->selectedColors[] = ['id' => '', 'percentage' => ''];
    }

    public function removeColor($index)
    {
        unset($this->selectedColors[$index]);
        $this->selectedColors = array_values($this->selectedColors);
    }

    public function save()
    {
        $this->validate([
            'article' => "required|string|max:100|unique:fabrics,article,{$this->fabric->id}",
            'catalog_id' => 'required|exists:catalogs,id',
            'selectedColors' => ['required', 'array', new CheckColorPercentage],
            // distinct чтобы один и тот же цвет не был выбран дважды
            'selectedColors.*.id' => 'required|exists:colors,id|distinct',
            'selectedColors.*.percentage' => 'required|integer|min:1|max:100',
        ]);

        $total = collect($this->selectedColors)->sum('percentage');
        if ($total !== 100) {
            $this->addError('selectedColors', "Общая сумма состава должна быть ровно 100%. Сейчас: {$total}%.");
            return;
        }

        DB::transaction(function () {
            $this->fabric->update([
                'article' => $this->article,
                'catalog_id' => $this->catalog_id,
                'is_archived' => $this->is_archived,
            ]);

            $syncData = collect($this->selectedColors)
                ->keyBy('id')
                ->map(fn($item) => ['percentage' => $item['percentage']]);

            $this->fabric->colors()->sync($syncData);
        });

        session()->flash('message', 'Ткань обновлена!');
        return redirect()->to(route('fabrics.index'));
    }    
    
    public function render()
    {
        // все ColorIds, которые уже выбраны пользователем
        $usedColorIds = collect($this->selectedColors)
            ->pluck('id')
            ->filter() // убираем пустые значения
            ->toArray();

        return view('fabric::livewire.fabric-edit', [
            'catalogs' => Catalog::all(),
            'allColors' => Color::all(),
            'usedColorIds' => $usedColorIds
        ]);
    }
}
