<?php

namespace App\Imports;

use Exception;
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
        if ($rows->isEmpty()) {
            throw new Exception(
                'El archivo está vacío'
            );
        }

        $requiredColumns = [
            'nombre',
            'correo',
            'telefono',
            'empresa',
            'puesto'
        ];

        $headers = array_keys($rows->first()->toArray());

        foreach ($requiredColumns as $column) {

            if (!in_array($column, $headers)) {

                throw new Exception(
                    "Falta la columna obligatoria: {$column}"
                );
            }
        }

        foreach ($rows as $index => $row) {

            if (empty($row['nombre'])) {
                continue;
            }

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
