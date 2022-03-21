<?php


namespace App\Service\Pdf;


class PdfGenerator
{
    const MARGIN_TOP = 3;
    const MARGIN_BOTTOM = 4;
    const MARGIN_LEFT = 7;
    const MARGIN_RIGHT = 1.5;

    public static function makeOptions($pdf, $folder, $footer = null)
    {
        $pdf->setTemporaryFolder($folder);
        $pdf->setOption('enable-local-file-access',true);
        $pdf->setOption('print-media-type', true);
        $pdf->setOption('margin-top', self::MARGIN_TOP);
        $pdf->setOption('margin-bottom', self::MARGIN_BOTTOM);
        $pdf->setOption('margin-left', self::MARGIN_LEFT);
        $pdf->setOption('margin-right', self::MARGIN_RIGHT);

        if ($footer) {
            $pdf->setOption('footer-html', $footer);
        }

        return $pdf;

    }

}
