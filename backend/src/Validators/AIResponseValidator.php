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

        // 2. Pemulihan Cerdas jika JSON terpotong di tengah jalan (Truncated JSON Recovery)
        if (!is_array($decoded) || !isset($decoded['answer'])) {
            $extractedAnswer = null;
            $extractedSteps = [];
            $extractedBlocks = [];

            // A. Coba perbaiki penutup JSON yang terpotong
            $repaired = self::tryRepairTruncatedJson($jsonStr);
            if ($repaired !== null) {
                $decoded = $repaired;
            } else {
                // B. Ekstraksi "answer" via Regex dari teks terpotong
                if (preg_match('/"answer"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $rawOutput, $m)) {
                    $extractedAnswer = stripcslashes($m[1]);
                }

                // C. Ekstraksi "steps" via Regex
                if (preg_match('/"steps"\s*:\s*\[(.*?)(\]|\z)/s', $rawOutput, $sm)) {
                    if (preg_match_all('/"((?:[^"\\\\]|\\\\.)*)"/s', $sm[1], $stepMatches)) {
                        foreach ($stepMatches[1] as $s) {
                            $cleanStep = trim(stripcslashes($s));
                            if (!empty($cleanStep) && !str_starts_with($cleanStep, '{')) {
                                $extractedSteps[] = htmlspecialchars($cleanStep, ENT_QUOTES, 'UTF-8');
                            }
                        }
                    }
                }

                // D. Sanitasi total jika answer tidak berhasil diekstrak via regex
                if (empty($extractedAnswer)) {
                    // Buang seluruh tag JSON dan kode kurung kurawal
                    $clean = preg_replace('/```(?:json)?|```/m', '', $rawOutput);
                    $clean = preg_replace('/"?(?:answer|steps|blocks|follow_up_question|opcode|category|fields|inputs|label)"?\s*:\s*/i', '', $clean);
                    $clean = preg_replace('/[{}\[\]"]/', '', $clean);
                    $clean = trim(preg_replace('/\s+/', ' ', $clean));
                    $extractedAnswer = !empty($clean) ? $clean : 'Wah, Ardena siap membantumu belajar Scratch! Coba tanyakan lagi ya! 🌟';
                }

                return [
                    'answer'             => $extractedAnswer,
                    'steps'              => $extractedSteps,
                    'blocks'             => [],
                    'follow_up_question' => null,
                ];
            }
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

    /**
     * Mencoba memperbaiki sintaks JSON yang terputus di tengah array blocks
     */
    private static function tryRepairTruncatedJson(string $jsonStr): ?array
    {
        // Cari blok terakhir yang lengkap
        $lastBlockPos = strrpos($jsonStr, '}');
        if ($lastBlockPos === false) {
            return null;
        }

        // Ambil potongan sampai kurung kurawal penutup terakhir
        $cut = substr($jsonStr, 0, $lastBlockPos + 1);

        // Coba tutup array blocks dan root object
        $candidates = [
            $cut . ']}',
            $cut . '}',
            $cut . '"]}',
        ];

        foreach ($candidates as $candidate) {
            $test = json_decode($candidate, true);
            if (is_array($test) && isset($test['answer'])) {
                return $test;
            }
        }

        return null;
    }
}

