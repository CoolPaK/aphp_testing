<?php

declare(strict_types=1);

namespace AphpTesting;

interface TableWrapperInterface
{
    /**
     * @param array<string, mixed> $values
     */
    public function insert(array $values): void;

    /**
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    public function update(int $id, array $values): array;

    public function delete(int $id): void;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get(): array;
}