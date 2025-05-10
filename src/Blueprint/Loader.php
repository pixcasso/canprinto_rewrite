<?php

declare(strict_types=1);

namespace Canprinto\Blueprint;

use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Schema;
use Opis\JsonSchema\Errors\ErrorFormatter;

/**
 * Lädt und validiert Blueprint-Dateien (JSON) gegen schema.json.
 *
 * Nutzung:
 *   $loader = new Loader( __DIR__ . '/../../assets/blueprint/schema.json' );
 *   $bp = $loader->fromFile( $jsonPath );
 */
final class Loader
{
    private Validator $validator;
    private string $schemaPath;

    public function __construct(string $schemaPath)
    {
        $this->validator  = new Validator();
        $this->schemaPath = $schemaPath;
    }

    /** @throws \RuntimeException Wenn JSON oder Schema invalid */
    public function fromFile(string $jsonFile): Blueprint
    {
        if (!file_exists($jsonFile)) {
            throw new \RuntimeException("Blueprint file not found: $jsonFile");
        }

        $data = json_decode(file_get_contents($jsonFile));
        if ($data === null) {
            throw new \RuntimeException("Malformed JSON in $jsonFile");
        }

        $schema  = Schema::fromJsonString(file_get_contents($this->schemaPath));
        $result  = $this->validator->schemaValidation($data, $schema);

        if (!$result->isValid()) {
            // Direktes, unformatiertes Dump der Fehlerstruktur
            throw new \RuntimeException(
                "Blueprint validation failed (raw):\n" .
                    print_r($result->getErrors(), true)
            );
        }


        return Blueprint::fromStdClass($data);
    }
}
