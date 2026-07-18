<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    public function show(string $code): Response
    {
        $qrCode = QrCode::query()
            ->where('code', $code)
            ->where('status', 'active')
            ->firstOrFail();

        $payload = route('visitor-registration.create', ['code' => $qrCode->code]);
        $svg = $this->generateSvg($payload);

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    protected function generateSvg(string $payload): string
    {
        $size = 240;
        $modules = 21;
        $moduleSize = (int) floor($size / $modules);
        $margin = 4;

        $matrix = array_fill(0, $modules, array_fill(0, $modules, 0));
        $this->drawFinderPattern($matrix, 0, 0);
        $this->drawFinderPattern($matrix, $modules - 7, 0);
        $this->drawFinderPattern($matrix, 0, $modules - 7);

        $hash = crc32($payload);
        $bytes = unpack('C*', $payload);

        for ($row = 0; $row < $modules; $row++) {
            for ($col = 0; $col < $modules; $col++) {
                if ($matrix[$row][$col] !== 0) {
                    continue;
                }

                $seed = (($hash + ($row * 17) + ($col * 31) + ($bytes[$row % count($bytes)] ?? 0)) % 100);
                $matrix[$row][$col] = $seed % 2 === 0 ? 1 : 0;
            }
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 '.$size.' '.$size.'">';
        $svg .= '<rect width="100%" height="100%" fill="white"/>';

        for ($row = 0; $row < $modules; $row++) {
            for ($col = 0; $col < $modules; $col++) {
                if ($matrix[$row][$col] === 1) {
                    $svg .= '<rect x="'.($margin + ($col * $moduleSize)).'" y="'.($margin + ($row * $moduleSize)).'" width="'.$moduleSize.'" height="'.$moduleSize.'" fill="black"/>';
                }
            }
        }

        $svg .= '</svg>';

        return $svg;
    }

    protected function drawFinderPattern(array &$matrix, int $startRow, int $startCol): void
    {
        $size = 7;
        $pattern = [
            [1, 1, 1, 1, 1, 1, 1],
            [1, 0, 0, 0, 0, 0, 1],
            [1, 0, 1, 1, 1, 0, 1],
            [1, 0, 1, 1, 1, 0, 1],
            [1, 0, 1, 1, 1, 0, 1],
            [1, 0, 0, 0, 0, 0, 1],
            [1, 1, 1, 1, 1, 1, 1],
        ];

        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                $matrix[$startRow + $row][$startCol + $col] = $pattern[$row][$col];
            }
        }
    }
}
