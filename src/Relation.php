<?php

namespace Glugox\ModelMeta;

abstract class Relation
{
    public function __construct(
        public string $name,
        public RelationType $type,
        private readonly ?string $relatedEntityName = null,
        private ?string $relatedKey = null,
        protected ?string $foreignKey = null,
        protected ?string $localKey = null,
        protected ?string $relationName = null,
        protected ?string $morphName = null
    ) {}

    abstract public function isToOne(): bool;
    abstract public function isToMany(): bool;

    public function getRelatedEntityName(): ?string
    {
        return $this->relatedEntityName;
    }

    public function getForeignKey(): ?string
    {
        return $this->foreignKey;
    }

    public function getLocalKey(): ?string
    {
        return $this->localKey;
    }

    public function getRelationName(): string
    {
        return $this->relationName ?? $this->name;
    }

    public function getRelatedKey(): ?string
    {
        return $this->relatedKey;
    }

    public function getMorphName(): ?string
    {
        return $this->morphName;
    }

    public function isPolymorphic(): bool
    {
        return in_array($this->type, [
            RelationType::MORPH_ONE,
            RelationType::MORPH_MANY,
            RelationType::MORPH_TO,
            RelationType::MORPH_TO_MANY,
            RelationType::MORPHED_BY_MANY,
        ]);
    }

    public function getMorphTypeKey(): ?string
    {
        return $this->isPolymorphic() && $this->morphName ? $this->morphName.'_type' : null;
    }

    public function getApiPath(): string
    {
        return strtolower($this->name).'s';
    }

    public function getRouteName(): string
    {
        return strtolower($this->name).'.index';
    }
}