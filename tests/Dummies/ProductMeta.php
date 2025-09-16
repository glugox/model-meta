<?php

namespace Dummies;

use Glugox\ModelMeta\Field;
use Glugox\ModelMeta\ModelMeta;
use Glugox\ModelMeta\Fields\{
    ID,
    Text,
    Number,
    Enum,
    File
};

class ProductMeta extends ModelMeta
{
    /**
     * Define fields for the Product resource.
     *
     * @return array<int, Field>
     */
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('name')->required()->main(),
            Number::make('price')->step(0.01)->required(),
            Enum::make('status', ['draft', 'published'])->default('draft'),
            File::make('manual')->nullable(),
            Number::make('weight')->default(0.0),
        ];
    }

    public function tableName(): string
    {
        return 'products';
    }
}