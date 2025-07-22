<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Entry;

class ProductStatusBar extends Entry
{
    protected string $view = 'infolists.components.product-status-bar';

    protected string $message = 'Hello';

    public function message(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getState(): mixed
    {
        // Get the product color from the record
        $record = $this->getRecord();
        $originalColor = $record?->color?->hex_code ?? '#6366f1';

        // Calculate the complementary (opposite) color
        $complementaryColor = $this->getComplementaryColor($originalColor);

        return [
            'hex_code' => $complementaryColor,
            'original_color' => $originalColor,
        ];
    }

    /**
     * Calculate the complementary color
     */
    private function getComplementaryColor(string $hexColor): string
    {
        // Remove the # if present
        $hex = ltrim($hexColor, '#');

        // Ensure we have a 6-character hex code
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calculate complementary color (255 - original value)
        $compR = 255 - $r;
        $compG = 255 - $g;
        $compB = 255 - $b;

        // Convert back to hex
        return sprintf('#%02x%02x%02x', $compR, $compG, $compB);
    }
}
