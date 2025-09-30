<?php

namespace Glugox\ModelMeta\Filters;

use Glugox\ModelMeta\FilterType;
use Glugox\ModelMeta\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EnumFilter extends BaseFilter implements Filter
{

    /**
     * Create a new filter instance.
     */
    public function __construct(
        protected string $column
    ) {
        parent::__construct($column, FilterType::ENUM);
    }

    /**
     * Make a new instance of the filter.
     */
    public static function make(string $column): self
    {
        return new self($column);
    }

    /**
     * Apply the filter to the given query.
     *
     * @param Builder<Model> $query
     * @param mixed $value
     * @return Builder<Model>
     */
    public function apply(Builder $query, mixed $value): Builder
    {

        if(! is_array($value)) {
            throw new \InvalidArgumentException('Enum filter value must be an array.');
        }

        $selected = $value['selected'] ?? null;
        if(! $selected) {
            return $query;
        }

        return $query->where($this->column, $selected);
    }

    public function key(): string
    {
        return 'enum_'.$this->column;
    }
}
