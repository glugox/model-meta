<?php

namespace Glugox\ModelMeta\Filters;

use Glugox\ModelMeta\FilterType;
use Glugox\ModelMeta\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DateFilter extends BaseFilter implements Filter {

    /**
     * Create a new filter instance.
     */
    public function __construct(
        protected string $column
    ) {
        parent::__construct($column, FilterType::DATE_RANGE);
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
     * @param \DateTime|array{
     *     min?: string,
     *     max?: string
     * } $value
     * @return Builder<Model>
     */
    public function apply(Builder $query, mixed $value): Builder
    {
        if (is_array($value)) {
            /** @var string $min*/
            $min= $value['min'] ?? null;
            $min = Carbon::parse($min);
            /** @var string $max */
            $max = $value['max'] ?? null;
            $max = Carbon::parse($max);
            if ($min && $max && $min < $max) {
                return $query->whereBetween($this->column, [$min, $max]);
            } elseif (isset($value['min'])) {
                return $query->whereDate($this->column, '>=', $min);
            } elseif (isset($value['max'])) {
                return $query->whereDate($this->column, '<=', $max);
            }
        } else {
            return $query->whereDate($this->column, '=', $value);
        }
        return $query;
    }

    public function key(): string
    {
        return 'date_'.$this->column;
    }
}