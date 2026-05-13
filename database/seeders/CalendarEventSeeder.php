<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CalendarEvent;
use Carbon\Carbon;

class CalendarEventSeeder extends Seeder
{
    public function run(): void
    {
        // No hardcoded events. 
        // Calendar events will come from the Event model (UKM events) and manual inputs.
    }
}
