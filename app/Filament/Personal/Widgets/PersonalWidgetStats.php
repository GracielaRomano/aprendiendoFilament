<?php

namespace App\Filament\Personal\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\User;
use App\Models\Timesheet;

class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approved Holidays', $this->getApprovedHoliday(Auth::user())),
            Stat::make('Total Working Hours', '3:12'),
        ];
    }
    protected function getPendingHoliday(User $user)
    {
        $totalPendingHoliday = Holiday ::where('user_id', $user->id)
        ->where('type', 'pending')->get()->count();
        return $totalPendingHoliday;
    }
    protected function getApprovedHoliday(User $user)
    {
        $totalApprovedHoliday = Holiday ::where('user_id', $user->id)
        ->where('type', 'approved')->get()->count();
        return $totalApprovedHoliday;
    }
    protected function getTotalWorkingHours(User $user)
    {
        $timesheets = Timesheet ::where('user_id', $user->id)
        ->where('type', 'work')->get();
        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            $startTime = Carbon::parse($timesheet->day-in);
            $finishTime = Carbon::parse($timesheet->day-out);
            $totalDuration = $finishTime->diffInSeconds($startTime);
            $sumSeconds += $totalDuration;
       
        }
        $tiempoFormato = gmdate('H:i:s', $sumSeconds);
        return $tiempoFormato;
    }
}
