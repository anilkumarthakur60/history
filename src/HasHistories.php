<?php

namespace Panoscape\History;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasHistories
{
    /**
     * Get all the model's histories.
     */
    public function histories():MorphMany
    {
        return $this->morphMany(History::class, 'model');
    }

    /**
     * Get all the model's histories.
     *
     * @return void
     */
    public static function bootHasHistories():void
    {
        if(!config('history.enabled')) {
            return;
        }

        if(in_array(app()->environment(), config('history.env_blacklist'))) {
            return;
        }

        if(app()->runningInConsole() && !config('history.console_enabled')) {
            return;
        }

        if(app()->runningUnitTests() && !config('history.test_enabled')) {
            return;
        }

        static::observe(HistoryObserver::class);
    }

    /**
     * Get the model's meta in history.
     *
     * @return array
     */
    public function getModelMeta($event): ?array
    {
        switch($event)
        {
            case 'updating':
                /*
                * Gets the model's altered values and tracks what had changed
                */
                $changes = $this->getDirty();

                $changed = [];
                foreach ($changes as $key => $value) {
                    if(static::isIgnored($this, $key)) continue;

                    $changed[] = ['key' => $key, 'old' => $this->getOriginal($key), 'new' => $this->$key];
                }
                return $changed;
            case 'created':
            case 'deleting':
            case 'restored':
                default:
                return null;
        }
    }

    public static function isIgnored($model, $key): bool
    {
        $blacklist = config('history.attributes_blacklist');
        $name = get_class($model);
        $array = isset($blacklist[$name])? $blacklist[$name]: null;
        return !empty($array) && in_array($key, $array);
    }

    /**
     * Get the model's label in history.
     *
     * @return string
     */
    public abstract function getModelLabel(): string;
}
