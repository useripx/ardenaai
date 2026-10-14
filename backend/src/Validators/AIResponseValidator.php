<?php

declare(strict_types=1);

namespace Ardena\Validators;

/**
 * AIResponseValidator
 *
 * Memvalidasi dan mengekstrak respons JSON terstruktur dari output model AI,
 * serta memastikan sanitasi data ramah anak sebelum dikirim ke frontend.
 */
class AIResponseValidator
{
    /**
     * Memvalidasi dan menormalisasi output AI
     */
    public static function parseAndValidate(string $rawOutput): array
    {
        $rawOutput = trim($rawOutput);

        // 1. Ekstrak blok JSON dari markdown code fence jika ada
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $rawOutput, $matches)) {
            $jsonStr = $matches[1];
        } elseif (preg_match('/\{[\s\S]*\}/', $rawOutput, $matches)) {
            $jsonStr = $matches[0];
        } else {
            $jsonStr = $rawOutput;
        }

        $decoded = json_decode($jsonStr, true);

        // 2. Jika bukan format JSON yang valid, jadikan seluruh teks sebagai answer ramah
        if (!is_array($decoded) || !isset($decoded['answer'])) {
            // Hapus sisa markdown ticks jika ada
            $cleanAnswer = trim(preg_replace('/^```(?:json)?|```$/m', '', $rawOutput));
            return [
                'answer'             => !empty($cleanAnswer) ? $cleanAnswer : 'Wah, Ardena siap membantumu belajar Scratch! Coba tanyakan lagi ya! 🌟',
                'steps'              => [],
                'blocks'             => [],
                'follow_up_question' => null,
            ];
        }

        // 3. Normalisasi bidang-bidang respons
        $answer = trim((string)($decoded['answer'] ?? ''));
        $steps = [];
        if (isset($decoded['steps']) && is_array($decoded['steps'])) {
            foreach ($decoded['steps'] as $step) {
                $stepText = trim((string)$step);
                if (!empty($stepText)) {
                    $steps[] = htmlspecialchars($stepText, ENT_QUOTES, 'UTF-8');
                }
            }
        }

        // 4. Validasi blok Scratch 3 via ScratchBlockValidator
        $rawBlocks = isset($decoded['blocks']) && is_array($decoded['blocks']) ? $decoded['blocks'] : [];
        $blocks = ScratchBlockValidator::validateBlocks($rawBlocks);

        $followUp = isset($decoded['follow_up_question']) && is_string($decoded['follow_up_question'])
            ? trim($decoded['follow_up_question'])
            : null;

        return [
            'answer'             => $answer,
            'steps'              => $steps,
            'blocks'             => $blocks,
            'follow_up_question' => $followUp,
        ];
    }
}
