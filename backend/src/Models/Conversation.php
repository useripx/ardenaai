<?php

declare(strict_types=1);

namespace Ardena\Models;

/**
 * Model Conversation — Entitas Sesi Percakapan Siswa
 */
class Conversation
{
    public function __construct(
        public string $id,
        public string $title,
        public string $preview = '',
        public array $messages = [],
        public string $updatedAt = ''
    ) {
        if (empty($this->updatedAt)) {
            $this->updatedAt = date('c');
        }
    }

    public static function fromArray(string $id, array $data): self
    {
        return new self(
            id: $id,
            title: (string)($data['title'] ?? 'Obrolan Scratch'),
            preview: (string)($data['preview'] ?? ''),
            messages: is_array($data['messages'] ?? null) ? $data['messages'] : [],
            updatedAt: (string)($data['updated_at'] ?? ($data['saved_at'] ?? date('c')))
        );
    }

    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'preview'        => $this->preview,
            'messages'       => $this->messages,
            'total_messages' => count($this->messages),
            'updated_at'     => $this->updatedAt,
        ];
    }
}
