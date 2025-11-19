<?php

declare(strict_types=1);


namespace hipanel\modules\server\helpers;

readonly class AssignHubsGroup
{
    public function __construct(
        private array $items,
        private ?string $name = null,
        private ?string $label = null,
    )
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function hasHeader(): bool
    {
        return $this->name !== null && $this->label !== null;
    }

    public function notEmpty(): bool
    {
        return !empty($this->items) && is_iterable($this->items);
    }
}
