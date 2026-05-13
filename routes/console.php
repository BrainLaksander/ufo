<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('announcements:publish')->everyMinute();
