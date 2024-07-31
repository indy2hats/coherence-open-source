<?php

namespace App\Exports;

use App\Repository\AssetRepository;
use App\Repository\AttributeRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetExcelExport implements FromView, WithColumnWidths, WithStyles
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        return view('assets.export', [
            'assets' => AssetRepository::getCsvAssetData($this->request),
            'assetValue' => AssetRepository::getList()->sum('value'),
            'attributes' => AttributeRepository::getAllAttributes(),
        ]);
    }

    public function columnWidths(): array
    {
        $attributes = AttributeRepository::getAllAttributes();
        $columnWidth['A'] = 36;
        $columnWidth['B'] = 16;
        $columnWidth['C'] = 20;
        $columnWidth['D'] = 20;
        $columnWidth['E'] = 56;
        $columnWidth['F'] = 30;
        foreach ($attributes as $index => $attribute) {
            $dynamicWidth = strlen($attribute->name) * 2;
            $columnWidth[chr(ord('G') + $index)] = $dynamicWidth;
        }

        return $columnWidth;
    }

    public function styles(Worksheet $sheet)
    {
        $attributeColumns = AttributeRepository::getAllAttributes()->count();
        $styles = [];

        $styles['A1'] = ['font' => ['size' => 13, 'bold' => true]];
        $styles['B1'] = ['font' => ['size' => 13, 'bold' => true]];
        $styles['C1'] = ['font' => ['size' => 13, 'bold' => true]];
        $styles['D1'] = ['font' => ['size' => 13, 'bold' => true]];
        $styles['E1'] = ['font' => ['size' => 13, 'bold' => true]];
        $styles['F1'] = ['font' => ['size' => 13, 'bold' => true]];

        $styles['A'] = ['font' => ['size' => 11]];
        $styles['B'] = ['font' => ['size' => 11]];
        $styles['C'] = ['font' => ['size' => 11]];
        $styles['D'] = ['font' => ['size' => 11]];
        $styles['E'] = ['font' => ['size' => 11]];
        $styles['F'] = ['font' => ['size' => 11]];

        for ($index = 0; $index < $attributeColumns; $index++) {
            $styles[chr(ord('G') + $index).'1'] = ['font' => ['size' => 13, 'bold' => true]];
            $styles[chr(ord('G') + $index)] = ['font' => ['size' => 11]];
        }

        return $styles;
    }
}
