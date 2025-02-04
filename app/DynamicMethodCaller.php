<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Number;

trait DynamicMethodCaller
{
    /**
     * List of dynamic methods with their respective prefixes.
     *
     * @var array
     */
    protected static array $dynamicMethods = [
        'array' => Arr::class,
        'carbon' => Carbon::class,
        'collect' => Collection::class,
        'html' => HtmlString::class,
        'num' => Number::class,
        'str' => Str::class,
    ];

    /**
     * Dynamically call methods from the classes with the specified prefix.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $arguments)
    {
        foreach (self::$dynamicMethods as $prefix => $class) {
            if (str_starts_with($method, $prefix . '_')) {
                $actualMethod = substr($method, strlen($prefix) + 1);
                $isClassString = is_string($class);

                if ($isClassString && method_exists($class, $actualMethod)) {
                    return $class::$actualMethod(...$arguments);
                } elseif (!$isClassString && method_exists($class, $actualMethod)) {
                    return $class->$actualMethod(...$arguments);
                }
            }
        }

        throw new \BadMethodCallException(__('system.error.method_not_found'));
    }

    /**
     * Set a new list of classes with their respective prefixes.
     *
     * @param mixed ...$dynamicMethods
     * @return void
     */
    protected static function setDynamicMethods(...$dynamicMethods): void
    {
        $newDynamicMethods = [];

        foreach ($dynamicMethods as $method) {
            if (is_array($method)) {
                $newDynamicMethods = array_merge($newDynamicMethods, $method);
            } else {
                $newDynamicMethods[] = $method;
            }
        }

        self::$dynamicMethods = $newDynamicMethods;
    }

    /**
     * Add one or multiple classes with their prefixes to the list of classes.
     * Accepts an array of classes with 'prefix' => 'class' format, or a class directly.
     *
     * @param mixed ...$dynamicMethods
     * @return void
     */
    protected static function addDynamicMethods(...$dynamicMethods): void
    {
        $newDynamicMethods = [];

        foreach ($dynamicMethods as $method) {
            if (is_array($method)) {
                $newDynamicMethods = array_merge($newDynamicMethods, $method);
            } else {
                $newDynamicMethods[] = $method;
            }
        }

        self::$dynamicMethods = array_merge(self::$dynamicMethods, $newDynamicMethods);
    }

    public static function getDynamicMethods()
    {
        return self::$dynamicMethods;
    }
}
