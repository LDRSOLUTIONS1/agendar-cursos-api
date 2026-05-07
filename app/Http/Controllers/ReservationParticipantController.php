<?php

namespace App\Http\Controllers;

use App\Imports\ParticipantsImport;
use App\Models\Reservation;
use App\Models\ReservationImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReservationParticipantController extends Controller
{

    public function import(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status != 4) {
            return response()->json([
                'message' => 'La reservación no está finalizada'
            ], 422);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $path = $request->file('file')->store(
            'reservation-imports',
            'public'
        );

        $import = ReservationImport::create([
            'reservation_id' => $reservation->id,
            'file_name' => $request->file('file')
                ->getClientOriginalName(),
            'file_path' => $path,
            'user_id' => auth()->id(),
        ]);

        Excel::import(
            new ParticipantsImport(
                $reservation->id,
                $import->id
            ),
            $request->file('file')
        );

        $import->update([
            'total_participants' => $reservation
                ->participants()
                ->where('reservation_import_id', $import->id)
                ->count()
        ]);

        return response()->json([
            'message' => 'Participantes importados correctamente'
        ]);
    }
}
