<?php

namespace Modules\Fabric\App\Http\Livewire;

use Livewire\Component;
use Modules\Fabric\Entities\Fabric;
use Modules\Fabric\Entities\Catalog;
use Modules\Fabric\Entities\Color;
use Illuminate\Support\Facades\DB;
use Modules\Fabric\App\Rules\CheckColorPercentage;

class FabricCreate extends Component
{
    // Поля формы
    public $article = '';
    public $catalog_id = '';
    public $selectedColors = []; // Формат: [['id' => 1, 'percentage' => 100]]

    /**
     * Правила валидации (включая уникальность в реальном времени)
     */
    protected function rules()
    {
        return [
            'article' => 'required|string|max:100|unique:fabrics,article',
            'catalog_id' => 'required|exists:catalogs,id',
            'selectedColors' => ['required', 'array', 'min:1', new CheckColorPercentage],
            'selectedColors.*.id' => 'required|exists:colors,id|distinct',
            'selectedColors.*.percentage' => 'required|integer|min:1|max:100',
        ];
    }

    /**
     * вызывается автоматически при изменении любого свойства
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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
        // Стандартная валидация, которая срабатывает при $total > 100, чтобы не отвлекать пользователя
        $this->validate();

        // 2. Строгая проверка суммы (ровно 100)
        $total = collect($this->selectedColors)->sum('percentage');
        if ($total !== 100) {
            // ошибка конкретно для поля selectedColors
            $this->addError('selectedColors', "Общая сумма состава должна быть ровно 100%. Сейчас: {$total}%.");
            return;
        }

        DB::transaction(function () {
            $fabric = Fabric::create([
                'article' => $this->article,
                'catalog_id' => $this->catalog_id,
            ]);

            $syncData = collect($this->selectedColors)
                ->keyBy('id')
                ->map(fn($item) => ['percentage' => $item['percentage']]);

            $fabric->colors()->sync($syncData);
        });

        $this->dispatch('fabric-created');

        session()->flash('message', 'Ткань успешно создана!');
        return redirect()->to('/fabrics');
    }

    public function render()
    {
        // все ColorIds, которые уже выбраны пользователем
        $usedColorIds = collect($this->selectedColors)
            ->pluck('id')
            ->filter() // убираем пустые значения
            ->toArray();

        return view('fabric::livewire.fabric-create', [
            'catalogs' => Catalog::all(),
            'allColors' => Color::all(),
            'usedColorIds' => $usedColorIds
        ]);
    }
}
