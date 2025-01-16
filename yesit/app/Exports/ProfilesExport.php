<?php

namespace App\Exports;

use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProfilesExport implements FromCollection
{
    public function collection()
    {
        return Profile::all();
    }
}
