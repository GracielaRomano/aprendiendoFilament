<?php

namespace App\Filament\Personal\Resources\HolidayResource\Pages;

use App\Filament\Personal\Resources\HolidayResource;
use App\Models\User;
use App\Mail\HolidayPending;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
        $data['user_id'] = Auth::user()->id;
        $data['type'] = 'pending';
        $userAdmin = User::find(2);
        $dataToSend = array(
            'day' => $data['day'],
            'name' => User::find($data['user_id'])->name,
            'email' => User::find($data['user_id'])->email,
        );
        Mail::to($userAdmin->email)->send(new HolidayPending($dataToSend));

        // Notification::make()
        //     ->title('Solicitud de Vacaciones Enviada')
        //     ->body("El dia:".$data['day'].'esta pendiente de aprobar')
        //     ->success()
        //     ->send();

        $recipient = auth()->user();
        Notification::make()
            ->title('Solicitud de Vacaciones Enviada')
            ->body("El dia:" .$data['day']. 'esta pendiente de aprobar')
            ->sendToDatabase($recipient);
        return $data;
    }
}
