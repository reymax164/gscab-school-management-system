<?php

namespace App\View\Components\Widgets;

use Carbon\Carbon;
use Illuminate\View\Component;

class Calendar extends Component
{
    public $month;

    public $daysInMonth;

    public $blankDays;

    public $events;

    public $isCurrentMonth;

    public $today;

    public function __construct($date = null, $events = [])
    {
        $currentDate = $date ? Carbon::parse($date) : Carbon::now();

        $this->month = $currentDate->format('F Y');
        $this->daysInMonth = $currentDate->daysInMonth;
        $this->blankDays = $currentDate->copy()->startOfMonth()->dayOfWeek;

        // Add these two lines
        $this->isCurrentMonth = $currentDate->isCurrentMonth();
        $this->today = Carbon::now()->day;

        $this->events = $events;
    }

    public function render()
    {
        return view('components.widgets.calendar');
    }
}
