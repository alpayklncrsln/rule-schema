<?php

namespace Alpayklncrsln\RuleSchema\Traits;

use Alpayklncrsln\RuleSchema\Rule;
use Alpayklncrsln\RuleSchema\RuleSchema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait withCacheTrait
{
    protected ?string $cacheName = null;
    protected ?Carbon $cacheTime = null;

    public static function cache(string $cacheName, Carbon $time, Rule ...$rules): self|array
    {
        $ruleSchema = new RuleSchema(...$rules);
        $ruleSchema->setCache($cacheName, $time);
        if ($ruleSchema->existsCacheData()) {
            ray('exists cache data');
            return $ruleSchema->getCache();
        }else{
            return $ruleSchema;
        }
    }

    protected function getCacheName(): ?string
    {
        return $this->cacheName;
    }
    public function setCache(string $name, Carbon $time): self
    {
        $this->cacheName = $name;
        $this->cacheTime = $time;
        return $this;
    }

    protected function getCacheTime(): ?Carbon
    {
        return $this->cacheTime;
    }

    public function setCacheTime(Carbon $time): void
    {
        $this->cacheTime = $time;
    }

    public function setCacheName(string $name): void
    {
        $this->cacheName = $name;
    }

    public function clearCache():void
    {
            Cache::forget($this->getCacheName());
    }

    public function getCache(): RuleSchema
    {
        return  Cache::get($this->getCacheName());
    }

    protected function setCacheData(): void
    {
        Cache::put($this->getCacheName(), $this, $this->getCacheTime());
    }

    protected function isCaching():bool
    {
        return !is_null($this->getCacheName());

    }
    protected function existsCacheData():bool
    {
        return $this->isCaching() && Cache::has($this->getCacheName());
    }

}
