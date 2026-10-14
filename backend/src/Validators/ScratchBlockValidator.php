<?php

declare(strict_types=1);

namespace Ardena\Validators;

/**
 * ScratchBlockValidator
 *
 * Memvalidasi dan menormalisasi struktur Scratch 3 blocks yang dihasilkan oleh AI
 * sebelum diteruskan ke frontend dan di-render di Canvas/Stage.
 */
class ScratchBlockValidator
{
    /**
     * Kategori resmi Scratch 3 beserta kode warna hex standar
     */
    public const VALID_CATEGORIES = [
        'motion'    => ['color' => '#4C97FF', 'name' => 'Gerakan'],
        'looks'     => ['color' => '#9966FF', 'name' => 'Tampilan'],
        'sound'     => ['color' => '#CF63CF', 'name' => 'Suara'],
        'events'    => ['color' => '#FFBF00', 'name' => 'Kejadian'],
        'control'   => ['color' => '#FFAB19', 'name' => 'Kontrol'],
        'sensing'   => ['color' => '#5CB1D6', 'name' => 'Sensor'],
        'operators' => ['color' => '#59C059', 'name' => 'Operator'],
        'variables' => ['color' => '#FF8C1A', 'name' => 'Variabel'],
        'myblocks'  => ['color' => '#FF6680', 'name' => 'Balok Saya'],
    ];

    /**
     * Memvalidasi dan menormalisasi daftar blok
     *
     * @param array $rawBlocks Daftar blok mentah dari respons AI
     * @return array Daftar blok yang telah divalidasi dan disanitasi
     */
    public static function validateBlocks(array $rawBlocks): array
    {
        $sanitizedBlocks = [];

        foreach ($rawBlocks as $index => $block) {
            if (!is_array($block)) {
                continue;
            }

            // Normalisasi kategori
            $category = strtolower(trim((string)($block['category'] ?? 'motion')));
            if (!array_key_exists($category, self::VALID_CATEGORIES)) {
                $category = 'motion'; // Default aman
            }

            // Sanitasi label dari script/HTML jahat
            $label = htmlspecialchars(trim((string)($block['label'] ?? '')), ENT_QUOTES, 'UTF-8');
            if (empty($label)) {
                continue;
            }

            $sanitizedBlocks[] = [
                'id'       => (string)($block['id'] ?? 'blk_' . ($index + 1)),
                'category' => $category,
                'color'    => self::VALID_CATEGORIES[$category]['color'],
                'category_name' => self::VALID_CATEGORIES[$category]['name'],
                'opcode'   => preg_replace('/[^a-zA-Z0-9_]/', '', (string)($block['opcode'] ?? 'motion_block')),
                'fields'   => is_array($block['fields'] ?? null) ? $block['fields'] : [],
                'inputs'   => is_array($block['inputs'] ?? null) ? $block['inputs'] : [],
                'label'    => $label,
            ];
        }

        return $sanitizedBlocks;
    }
}
