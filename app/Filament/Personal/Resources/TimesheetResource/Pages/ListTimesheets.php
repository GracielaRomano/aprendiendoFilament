<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Timesheet;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
            Action::make('inWork')
            ->label('Entrar a trabajar')
            ->color('success')
            ->requiresConfirmation()
            ->action(function () {
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = $user->id;
                $timesheet->day_in = Carbon:: now();
                $timesheet->day_out = Carbon:: now();
                $timesheet->type = 'work';
                $timesheet->save();
            }),
            Action::make('inPause')
            ->label('Comenzar Pausa')
            ->color('danger')
            ->requiresConfirmation(),
            Actions\CreateAction::make(),
        ];
    }
}
