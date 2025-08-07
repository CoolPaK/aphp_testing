<?php

declare(strict_types=1);

namespace AphpTesting;

class UserTableWrapper implements TableWrapperInterface
{
    /** @var array<int, array<string, mixed>> */
    private array $rows = [];

    /**
     * @param array<string, mixed> $values
     */
    public function insert(array $values): void
    {
        $this->rows[] = $values;
    }

    /**
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    public function update(int $id, array $values): array
    {
        foreach ($this->rows as &$row) {
            if (isset($row['id']) && $row['id'] === $id) {
                $row = array_merge($row, $values);
                return $row;
            }
        }

        $newRow = ['id' => $id] + $values;
        $this->rows[] = $newRow;
        return $newRow;
    }

    public function delete(int $id): void
    {
        foreach ($this->rows as $key => $row) {
            if (isset($row['id']) && $row['id'] === $id) {
                unset($this->rows[$key]);
                $this->rows = array_values($this->rows);
                return;
            }
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function get(): array
    {
        return $this->rows;
    }
}