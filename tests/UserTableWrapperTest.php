<?php

declare(strict_types=1);

namespace AphpTesting\Tests;

use AphpTesting\UserTableWrapper;
use PHPUnit\Framework\TestCase;

class UserTableWrapperTest extends TestCase
{
    private UserTableWrapper $table;

    protected function setUp(): void
    {
        $this->table = new UserTableWrapper();
    }

    /**
     * @dataProvider insertDataProvider
     * @param array<string, mixed> $values
     * @param array<int, array<string, mixed>> $expected
     */
    public function testInsert(array $values, array $expected): void
    {
        $this->table->insert($values);
        $this->assertSame($expected, $this->table->get());
    }

    /**
     * @return array<string, array{array<string, mixed>, array<int, array<string, mixed>>}>
     */
    public function insertDataProvider(): array
    {
        return [
            'simple insert' => [
                ['id' => 1, 'name' => 'John'],
                [['id' => 1, 'name' => 'John']]
            ],
            'another insert' => [
                ['id' => 2, 'name' => 'Jane'],
                [['id' => 2, 'name' => 'Jane']]
            ]
        ];
    }

    /**
     * @dataProvider updateDataProvider
     * @param array<int, array<string, mixed>> $initialData
     * @param array<string, mixed> $expected
     */
    public function testUpdate(int $id, array $values, array $initialData, array $expected): void
    {
        foreach ($initialData as $row) {
            $this->table->insert($row);
        }
        $result = $this->table->update($id, $values);
        $this->assertSame($expected, $result);
        $this->assertContains($result, $this->table->get());
    }

    /**
     * @return array<string, array{int, array<string, mixed>, array<int, array<string, mixed>>, array<string, mixed>}>
     */
    public function updateDataProvider(): array
    {
        return [
            'update existing' => [
                1,
                ['name' => 'Updated John'],
                [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']],
                ['id' => 1, 'name' => 'Updated John']
            ],
            'update non-existing' => [
                3,
                ['name' => 'New User'],
                [['id' => 1, 'name' => 'John']],
                ['id' => 3, 'name' => 'New User']
            ]
        ];
    }

    /**
     * @dataProvider deleteDataProvider
     * @param array<int, array<string, mixed>> $initialData
     * @param array<int, array<string, mixed>> $expected
     */
    public function testDelete(int $id, array $initialData, array $expected): void
    {
        foreach ($initialData as $row) {
            $this->table->insert($row);
        }
        $this->table->delete($id);
        $this->assertSame($expected, $this->table->get());
    }

    /**
     * @return array<string, array{int, array<int, array<string, mixed>>, array<int, array<string, mixed>>}>
     */
    public function deleteDataProvider(): array
    {
        return [
            'delete existing' => [
                1,
                [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']],
                [0 => ['id' => 2, 'name' => 'Jane']] // Изменено с [1 => ...] на [0 => ...]
            ],
            'delete non-existing' => [
                3,
                [['id' => 1, 'name' => 'John']],
                [0 => ['id' => 1, 'name' => 'John']] // Добавлен явный индекс 0
            ]
        ];
    }

    public function testGet(): void
    {
        $this->assertSame([], $this->table->get());
        $this->table->insert(['id' => 1, 'name' => 'John']);
        $this->assertSame([0 => ['id' => 1, 'name' => 'John']], $this->table->get());
    }
}