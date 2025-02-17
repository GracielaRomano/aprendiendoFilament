<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Mail\HolidayApproved;
use App\Mail\HolidayDeclined;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
{
    $record->update($data);

    //Enviamos el email solo si la solicitud es aprobada
    if ($record->type == 'approved') {
        $user = User::find($record->user_id);
        $data = array(
            'name' => $user->name,
            'email' => $user->email,
            'day' => $record->day,
        );
        Mail::to($user)->send(new HolidayApproved($data));

        $recipient = $user;
        Notification::make()
            ->title('Solicitud de Vacaciones')
            ->body("El dia: " .$data['day'].' esta Aprobado')
            ->sendToDatabase($recipient);
    }else if ($record->type == 'declined') {
        $user = User::find($record->user_id);
        $data = array(
            'name' => $user->name,
            'email' => $user->email,
            'day' => $record->day,
        );
        Mail::to($user)->send(new HolidayDeclined($data));

        $recipient = $user;
        Notification::make()
            ->title('Solicitud de Vacaciones ')
            ->body("El dia: " .$data['day'].'  ha sido rechazado')
            ->sendToDatabase($recipient);
    }
 
 
    return $record;
}
}
