<?php
namespace Irain\ExportExcel;

use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel {
    private $config;
    private $data;
    private $cacheDriver;
    private $spreadsSheet;
    private $char = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z'
    ];
    public function __construct($config, array $data)
    {
        if (empty($config)) {
            new \Exception('Config Can Not Empty.');
        }
        if (empty($data)) {
            new \Exception('Sheet Data Can Not Empty.');
        }
        $this->config = $config;
        $this->data = $data;
        $this->spreadsSheet = new Spreadsheet;
        $this->cacheDriver = (new CacheDriver())->setCacheDriver($this->config['cache_driver']);
        if (! empty($this->cacheDriver)) {
            Settings::setCache($this->cacheDriver);
        }
    }

    public function downloadPath()
    {
        return $this->config['path'] ? $this->config['path'] . '/' : './';
    }

    public function write()
    {
        // 创建Table Header
        $this->createTableHead();
        dd(1);
    }

    private function createTableHead()
    {
       $this->createSheetData(1);
    }

    private function createSheetData($sheetNumber)
    {
        $number = 0;
        foreach ($this->config['table_header'] as $value) {
            $this->spreadsSheet->getActiveSheet()->setCellValue($this->numberToString($number, $sheetNumber), $value);
            $number++;
        }
        $writer = new Xlsx($this->spreadsSheet);
        $writer->save($this->buildDownloadFileName());
    }

    private function buildDownloadFileName($ext = '.xls')
    {
        return $this->downloadPath() . $this->config['name'] . $ext;
    }

    private function numberToString($number, $sheetNumber)
    {
        return $this->char[$number] . $sheetNumber;
    }

}

