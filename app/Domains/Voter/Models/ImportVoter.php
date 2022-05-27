<?php
namespace App\Domains\Voter\Models;

use App\Domains\Voter\Models\ImportVotersPerSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportVoter implements WithMultipleSheets
{
    public function sheets() : array
    {
        return [
            'SAMPALOC' => new ImportVotersPerSheet('Sampaloc'),
            'Anibong' => new ImportVotersPerSheet('Anibong'),
            'Dingin' => new ImportVotersPerSheet('Dingin'),
            'Layugan' => new ImportVotersPerSheet('Layugan'),
            'POB1' => new ImportVotersPerSheet('POB1'),
            'San Isidro' => new ImportVotersPerSheet('San Isidro'),
            'MAULAWIN' => new ImportVotersPerSheet('MAULAWIN'),
            'BUBOY' => new ImportVotersPerSheet('BUBOY'),
            'BINAN' => new ImportVotersPerSheet('BINAN'),
            'MAGDAPIO' => new ImportVotersPerSheet('MAGDAPIO'),
            'SABANG' => new ImportVotersPerSheet('SABANG'),
            'PINAGSANJAN' => new ImportVotersPerSheet('PINAGSANJAN'),
            'LAMBAC' => new ImportVotersPerSheet('LAMBAC'),
            'POB2' => new ImportVotersPerSheet('POB2'),
            'CABANBANAN' => new ImportVotersPerSheet('CABANBANAN'),
            'CALUSICHE' => new ImportVotersPerSheet('CALUSICHE'),
        ];
    }
}
