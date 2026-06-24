<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseCategoryExport implements FromCollection, WithHeadings, WithMapping
{
    private int $subIndex = 0;
    private $query;

    public function __construct($query)
    {
        $this->query = $query->with('children');
    }

    /**
     * Data Collection
     */
    public function collection()
    {
        $mainCategories = $this->query->get();
        $exportData = collect();
        foreach ($mainCategories as $mainCategory) {
            //Main Category
            $exportData->push([
                'is_sub' => false,
                'code'   => $mainCategory->code,
                'name'   => $mainCategory->name,
                'type'   => $mainCategory->type->label()
            ]);

            //  Sub Categories
            if ($mainCategory->children) {
                foreach ($mainCategory->children as $child) {
                    $exportData->push([
                        'is_sub' => true,
                        'code'   => null,
                        'name'   => $child->name,
                        'type'   => $child->type->label()
                    ]);
                }
            }
        }

        return $exportData;
    }

    /**
     * Excel Column Headings
     */
    public function headings(): array
    {
        return [
            'စဉ်',
            'အကြောင်းအရာ',
            'အမျိုးအစား',
        ];
    }
    private function convertToMyanmarNumber($number): string
    {
        $engNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $mmNumbers  = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];

        return str_replace($engNumbers, $mmNumbers, (string)$number);
    }

    /**
     * row by row
     */
    public function map($row): array
    {
        if ($row['is_sub']) {
            $this->subIndex++;
            $subIndex = $this->convertToMyanmarNumber($this->subIndex) . "။";

            return [
                $subIndex,
                $row['name'],
                $row['type']
            ];
        }

        return [
            $row['code'],
            $row['name'],
            $row['type']
        ];
    }
}
