<?php

namespace Modules\Fabric\App\Http\Livewire;

use Livewire\Component;
use Modules\Fabric\Entities\Color;

class ColorList extends Component
{
    public $colorId, $name, $hex_code = '#000000', $isEditMode = false;

    public function create() {
        $this->reset(['name', 'hex_code', 'colorId', 'isEditMode']);
        $this->dispatch('open-color-modal');
    }

    public function edit($id) {
        $color = Color::findOrFail($id);
        $this->colorId = $color->id;
        $this->name = $color->name;
        $this->hex_code = $color->hex_code ?? '#000000';
        $this->isEditMode = true;
        $this->dispatch('open-color-modal');
    }

    public function save() {
        $this->validate([
            'name' => "required|min:2|unique:colors,name,{$this->colorId}",
            'hex_code' => 'required|string|size:7'
        ]);

        Color::updateOrCreate(['id' => $this->colorId], [
            'name' => $this->name,
            'hex_code' => $this->hex_code
        ]);

        $this->dispatch('close-color-modal');
    }

    public function delete($id) {
        $color = Color::findOrFail($id);
        if ($color->fabrics()->exists()) {
            session()->flash('error', 'Цвет используется в тканях и не может быть удален.');
            return;
        }
        $color->delete();
    }

    public function render() {
        return view('fabric::livewire.color-list', [
            'colors' => Color::withCount('fabrics')->get()
        ]);
    }
}
