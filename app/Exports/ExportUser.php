<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportUser implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return User::query()->whereNotNull('survey_id')->get();
    }

    public function headings(): array
    {
        return [
            'Id',
            'Email/Phone',
        ];
    }

    public function map($row): array
    {
        return [
            $row['id'],
            $row['email'],
        ];
    }
}
