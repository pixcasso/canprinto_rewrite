<?php
declare(strict_types=1);

namespace Canprinto\Blueprint;

/**
 * Immutable Value-Object für einen Blueprint.
 *  – Alle Felder sind öffentlich READ-ONLY (PHP 8.2).
 */
final class Blueprint
{
    public function __construct(
        public readonly string $title,
        public readonly float  $priceMarkup,
        public readonly object $manufacturer,
        public readonly object $properties,
        public readonly object|null $renderRoadmap = null,
    ) {}

    /** Factory für stdClass → Blueprint */
    public static function fromStdClass(\stdClass $data): self
    {
        return new self(
            $data->title,
            (float) ($data->price_markup ?? 0),
            $data->manufacturer,
            $data->properties,
            $data->render_roadmap ?? null
        );
    }
}
