<?php

namespace Glugox\ModelMeta\Requests;

use Illuminate\Http\Request;
use Glugox\ModelMeta\ModelMetaResolver;
use Illuminate\Support\Str;
use RuntimeException;

class MetaRequest extends Request
{

    /**
     * Resolve the ModelMeta class name for a given Eloquent model or model class name.
     *
     * Default: App\\Meta\\{ModelName}Meta
     * e.g. App\Meta\UserMeta for App\Models\User
     */
    protected static string $defaultModelNamespace = 'App\\Models';
    public static function setDefaultModelNamespace(string $namespace): void
    {
        static::$defaultModelNamespace = trim($namespace, '\\');
    }

    protected ?string $resourceName = null;
    protected ?string $modelClass = null;

    protected ?string $metaClass = null;

    /**
     * Get resource name from route (e.g. "addresses" from "addresses.index").
     * @return string The resource name (e.g. "addresses")
     */
    public function resourceName(): string
    {
        if (! $this->resourceName) {
            $routeName = $this->route()->getName(); // "addresses.index"
            $this->resourceName = explode('.', $routeName)[0]; // "addresses"
        }

        return $this->resourceName;
    }

    /**
     * Resolve the Eloquent model class for this request.
     */
    public function modelClass(): string
    {
        if (! $this->modelClass) {
            $modelClass = Str::singular(Str::studly($this->resourceName()));
            $shortName  = class_basename($modelClass);
            $this->modelClass  = static::$defaultModelNamespace . '\\' . $shortName;

            if (! class_exists($this->modelClass)) {
                throw new RuntimeException("ModelMeta class not found for model [{$modelClass}] at [{$this->modelClass}]");
            }
        }

        return $this->modelClass;
    }

    /**
     * Resolve the Meta class for this request.
     */
    public function metaClass(): string
    {
        if (! $this->metaClass) {
            $this->metaClass = ModelMetaResolver::resolve($this->modelClass());
        }

        return $this->metaClass;
    }

    /**
     * Instantiate the Meta object.
     */
    public function meta(): object
    {
        return ModelMetaResolver::make($this->modelClass());
    }
}
