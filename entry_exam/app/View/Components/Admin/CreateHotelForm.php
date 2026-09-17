<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Prefecture;

class CreateHotelForm extends Component
{
    /**
     * @var mixed
     */
    public $prefectures;

    /**
     * @var mixed
     */
    public $hotel;

    /**
     * Create a new component instance.
     */
    public function __construct($prefectures = null, $hotel = null)
    {
        $this->prefectures = $prefectures ?? Prefecture::orderBy('prefecture_id')->get();
        $this->hotel = $hotel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.create-hotel-form');
    }
}
