<?php

declare(strict_types=1);

namespace Ardena\Models;

/**
 * Model ScratchBlock — Entitas Blok Puzzle Visual Scratch 3
 */
class ScratchBlock
{
    public const COLOR_MAP = [
        'motion'    => '#4C97FF', // Biru
        'looks'     => '#9966FF', // Ungu
        'sound'     => '#CF63CF', // Pink Magenta
        'events'    => '#FFBF00', // Kuning Emas
        'control'   => '#FFAB19', // Oranye
        'sensing'   => '#5CB1D6', // Biru Muda Cyan
        'operators' => '#59C059', // Hijau
        'variables' => '#FF8C1A', // Oranye Tua
        'myblocks'  => '#FF6680', // Merah Muda
    ];

    public function __construct(
        public string $id,
        public string $category,
        public string $opcode,
        public string $label,
        public ?string $color = null,
        public array $fields = [],
        public array $inputs = []
    ) {
        if ($this->color === null) {
            $this->color = self::getColorForCategory($this->category);
        }
    }

    public static function getColorForCategory(string $category): string
    {
        $cat = strtolower(trim($category));
        return self::COLOR_MAP[$cat] ?? self::COLOR_MAP['motion'];
    }

    public static function fromArray(array $data): self
    {
        $category = (string)($data['category'] ?? 'motion');
        return new self(
            id: (string)($data['id'] ?? ('blk_' . uniqid())),
            category: $category,
            opcode: (string)($data['opcode'] ?? 'motion_movesteps'),
            label: (string)($data['label'] ?? ''),
            color: isset($data['color']) ? (string)$data['color'] : null,
            fields: is_array($data['fields'] ?? null) ? $data['fields'] : [],
            inputs: is_array($data['inputs'] ?? null) ? $data['inputs'] : []
        );
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id,
            'category' => $this->category,
            'opcode'   => $this->opcode,
            'label'    => $this->label,
            'color'    => $this->color,
            'fields'   => $this->fields,
            'inputs'   => $this->inputs,
        ];
    }
}
