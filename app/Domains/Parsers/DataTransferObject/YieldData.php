<?php

namespace App\Domains\Parsers\DataTransferObject;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class YieldData extends DataTransferObject
{
    /** @var string */
    public $income_type;

    /** @var int */
    public $source_data_id;

    /** @var \Carbon\Carbon */
    public $payed_at;

    /** @var \Carbon\Carbon */
    public $negociated_at;

    /** @var float */
    public $amount;

    public static function fromParser(array $data, int $sourceId): self
    {
        return new self([
            'source_data_id' => $sourceId,
            'income_type' => strtolower($data['et']),
            'payed_at' => Carbon::createFromFormat('d/m/Y', $data['pd']),
            'negociated_at' => Carbon::createFromFormat('d/m/Y', $data['ed']),
            'amount' => $data['ov'],
        ]);
    }
}
