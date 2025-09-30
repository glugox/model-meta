<?php

namespace Glugox\ModelMeta\Filters;


use Glugox\ModelMeta\Contracts\Filter;
use Glugox\ModelMeta\FilterType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class TimestampFilter extends BaseFilter implements Filter
{
    /**
     * Create a new filter instance.
     */
    public function __construct(
        protected string $column
    ) {
        parent::__construct($column, FilterType::TIMESTAMP);
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
     *     from?: string,
     *     to?: string
     * } $value
     * @return Builder<Model>
     */
    public function apply(Builder $query, mixed $value): Builder
    {
        if (is_array($value)) {
            /** @var string $from */
            $from = $value['from'] ?? null;
            /** @var string $to */
            $to = $value['to'] ?? null;
            if ($from && $to && $from > $to) {
                return $query->whereBetween($this->column, [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay()
                ]);
            } elseif (isset($value['from'])) {
                return $query->where($this->column, '>=', Carbon::parse($from)->startOfDay());
            } elseif (isset($value['to'])) {
                return $query->where($this->column, '<=', Carbon::parse($to)->endOfDay());
            }
        } else {
            return $query->whereDate($this->column, '=', Carbon::parse($value)->toDateString());
        }
        return $query;
    }

    /**
     * Get the key for the filter.
     */
    public function key(): string
    {
        return 'timestamp_'.$this->column;
    }
}