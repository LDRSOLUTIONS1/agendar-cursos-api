<?php

namespace App\Imports;

use App\Models\ReservationParticipant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantsImport implements ToCollection, WithHeadingRow
{
    protected $reservationId;
    protected $importId;

    public function __construct($reservationId, $importId)
    {
        $this->reservationId = $reservationId;
        $this->importId = $importId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $exists = ReservationParticipant::where([
                'reservation_id' => $this->reservationId,
                'email' => $row['correo'] ?? null,
                'full_name' => $row['nombre'],
            ])->exists();

            if ($exists) {
                continue;
            }

            ReservationParticipant::create([
                'reservation_id' => $this->reservationId,
                'reservation_import_id' => $this->importId,

                'full_name' => $row['nombre'],
                'email' => $row['correo'] ?? null,
                'phone' => $row['telefono'] ?? null,
                'company' => $row['empresa'] ?? null,
                'position' => $row['puesto'] ?? null,
            ]);
        }
    }
}
