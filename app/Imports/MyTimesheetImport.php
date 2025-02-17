<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Timesheet;
use App\Models\Calendar;
use Carbon\Carbon;


class MyTimesheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //
        foreach ($rows as $row)
        {
            // dd($row);
            $calendar_id = Calendar::where('name',$row['calendario'])->first()->id;
            
            if($calendar_id != null){
                Timesheet::create([
                    'calendar_id' => $calendar_id->id,
                    'user_id' => Auth::user()->id,
                    'type' => $row['tipo'],
                    'day_in' => $row['hora_de_entrada'],
                    'day_out' => $row['hora_de_salida'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
