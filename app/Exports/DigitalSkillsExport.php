<?php

namespace App\Exports;

use App\Models\DigitalSkill;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DigitalSkillsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DigitalSkill::withTrashed()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Deleted At',
            'Created At',
            'Updated At',
        ];
    }
}
