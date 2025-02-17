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
            Stat::make('Total Working Hours',$this->getTotalWorkingHours(Auth::user())),
            Stat::make('Total Hours of Pause',$this->getTotalPauseHours(Auth::user())),
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
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')->whereDate('created_at', Carbon::today())->get();

        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);
            $totalDuration = $startTime->diffInSeconds($finishTime);
            $sumSeconds = $sumSeconds + $totalDuration;
       
        }
        
        $tiempoFormato = gmdate('H:i:s', $sumSeconds);
        return $tiempoFormato;
    }
    protected function getTotalPauseHours(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'pause')->whereDate('created_at', Carbon::today())->get();

        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);
            $totalDuration = $startTime->diffInSeconds($finishTime);
            $sumSeconds = $sumSeconds + $totalDuration;
       
        }
        
        $tiempoFormato = gmdate('H:i:s', $sumSeconds);
        return $tiempoFormato;
    }
    
}