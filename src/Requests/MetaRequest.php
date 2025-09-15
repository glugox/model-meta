<?php

namespace Glugox\ModelMeta\Requests;

use Glugox\ModelMeta\ModelMeta;
use Illuminate\Foundation\Http\FormRequest;
use Glugox\ModelMeta\ModelMetaResolver;
use Illuminate\Support\Str;
use RuntimeException;

class MetaRequest extends FormRequest
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
     * Rules for Laravel FormRequest validation.
     *
     * @return array<string, string[]> The validation rules.
     */
    public function rules(): array
    {
        $method = $this->method();

        if ($method === 'POST') {
            return $this->meta()->rules()['store'];
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            return $this->meta()->rules()['update'];
        }

        // fallback
        return [];
    }


    /**
     * Get resource name from route or URI.
     * e.g. "addresses" from "addresses.index" or "/api/addresses"
     *
     * @return string The resource name (plural, snake-case)
     */
    public function resourceName(): string
    {
        if (! $this->resourceName) {
            $routeName = $this->route()?->getName();

            if (! $routeName) {
                throw new RuntimeException("Cannot resolve resource name: route has no name.");
            }

            $segments = explode('.', $routeName);

            // Remove common prefixes like 'api', 'admin', etc.
            $prefixesToSkip = ['api', 'admin', 'v1', 'v2'];
            $segments = array_filter($segments, fn($s) => ! in_array($s, $prefixesToSkip));

            // Take the first remaining segment as resource name
            $this->resourceName = array_shift($segments);

            if (! $this->resourceName) {
                throw new RuntimeException("Cannot resolve resource name from route: {$routeName}");
            }
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
    public function meta(): ModelMeta
    {
        return ModelMetaResolver::make($this->modelClass());
    }
}
