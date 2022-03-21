<?php


namespace App\Service\ExcelServices;


use PhpOffice\PhpSpreadsheet\Spreadsheet;

class EntityExport
{

    public function makeEntitySheet($fields, $excel, $repository, $title, $orderBy = 'name', $order = 'ASC')
    {
        $projectSheet = $excel->createSheet();
        $projectSheet->setTitle($title);
        $excel->setActiveSheetIndexByName($title);
        $row = 1;
        $col = 1;
        //entetes de colonnes
        foreach ($fields as $name => $method) {
            $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $name);
            $col++;
        }
        $col=1;
        $row++;
        //Saisie des données
        foreach ($repository->findBy([], [$orderBy => $order]) as $entity) {
            foreach ($fields as $name => $method) {
                $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $entity->{$method}());
                $col++;
            }
            $col=1;
            $row++;
        }
    }

    public function style(Spreadsheet $excel)
    {
        $headStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $cellStyle = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'autosize' => true,
        ];
        $sheets = $excel->getAllSheets();

        $excel->getDefaultStyle()->applyFromArray($cellStyle);
        foreach ($sheets as $sheet) {
            $excel->setActiveSheetIndexByName($sheet->getTitle());
            $excel->getActiveSheet()->getStyle('A1:' . $excel->getActiveSheet()->getHighestColumn() . '1')->applyFromArray($headStyle);
            foreach (range('A',$excel->getActiveSheet()->getHighestColumn()) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }
    }
}
