<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use App\Models\Timesheet;
use App\Imports\MyTimesheetImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use EightyNine\ExcelImport\ExcelImportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        if($lastTimesheet == null){
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
                    $timesheet->type = 'work';
                    $timesheet->save();
                }),
                Actions\CreateAction::make(),
            ];
       }
       
        return [
            
            Action::make('inWork')
            ->label('Entrar a trabajar')
            ->color('success')
            ->visible(!$lastTimesheet->day_out == null)
            ->disabled($lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function () {
                $user = Auth::user();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = $user->id;
                $timesheet->day_in = Carbon:: now();
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                ->title('Iniciaste el trabajo')
                ->color('success')
                ->success()
                ->send();
            }),
            Action::make('stopWork')
            ->label('Parar Trabajo')
            ->color('success')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type!='pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function () use ($lastTimesheet)  {
                $lastTimesheet->day_out = Carbon:: now();
                $lastTimesheet->save();

                Notification::make()
                ->title('Paraste el trabajo')
                ->color('success')
                ->success()
                ->send();
            }),
            Action::make('inPause')
            ->label('Comenzar Pausa')
            ->color('danger')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type!='pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use ($lastTimesheet) {
                $lastTimesheet->day_out = Carbon:: now();
                $lastTimesheet->save();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon:: now();
                $timesheet->type = 'pause';
                $timesheet->save();

                Notification::make()
                ->title('Iniciaste la pausa')
                ->color('danger')
                ->danger()
                ->send();
            }),
            Action::make('stopPause')
            ->label('Parar Pausa')
            ->color('danger')
            ->visible($lastTimesheet->day_out == null && $lastTimesheet->type=='pause')
            ->disabled(!$lastTimesheet->day_out == null)
            ->requiresConfirmation()
            ->action(function() use ($lastTimesheet) {
                $lastTimesheet->day_out = Carbon:: now();
                $lastTimesheet->save();
                $timesheet = new Timesheet();
                $timesheet->calendar_id = 1;
                $timesheet->user_id = Auth::user()->id;
                $timesheet->day_in = Carbon:: now();
                $timesheet->type = 'work';
                $timesheet->save();

                Notification::make()
                ->title('Paraste la pausa')
                ->color('danger')
                ->danger()
                ->send();
            }),
            Actions\CreateAction::make(),
            ExcelImportAction::make()->color('primary')->use(MyTimesheetImport::class),
        ];
    }
}
