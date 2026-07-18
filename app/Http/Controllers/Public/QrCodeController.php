<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    public function show(string $code): Response
    {
        $qrCode = QrCode::query()
            ->where('code', $code)
            ->where('status', 'active')
            ->firstOrFail();

        $url = url("/qr/{$qrCode->code}");

        $builder = new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 300,
            margin: 10,
        );

        $result = $builder->build();

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'inline; filename="'.$qrCode->code.'-qr.png"',
        ]);
    }

    public function download(string $code): Response
    {
        $qrCode = QrCode::query()
            ->where('code', $code)
            ->where('status', 'active')
            ->firstOrFail();

        $url = url("/qr/{$qrCode->code}");

        $builder = new Builder(
            writer: new PngWriter(),
            data: $url,
            size: 300,
            margin: 10,
        );

        $result = $builder->build();

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'attachment; filename="'.$qrCode->code.'-qr.png"',
        ]);
    }
}
