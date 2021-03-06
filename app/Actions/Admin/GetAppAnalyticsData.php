<?php

namespace App\Actions\Admin;

use App\Actions\Link\GenerateLinkReport;
use Common\Admin\Analytics\Actions\GetAnalyticsData;
use Common\Admin\Analytics\Actions\GetDemoAnalyticsData;
use Common\Admin\Analytics\Actions\GetGoogleAnalyticsData;
use Common\Admin\Analytics\Actions\GetNullAnalyticsData;
use Common\Admin\Analytics\AnalyticsController;
use Exception;
use Str;

class GetAppAnalyticsData implements GetAnalyticsData
{
    public function execute($channel)
    {
        if ($channel === AnalyticsController::DEFAULT_CHANNEL) {
            if (config('common.site.demo')) {
                return app(GetDemoAnalyticsData::class)->execute($channel);
            } else {
                return $this->getGoogleAnalyticsData()->execute($channel);
            }
        } else {
            return app(GenerateLinkReport::class)->execute(request()->all());
        }
    }

    /**
     * @return GetGoogleAnalyticsData|GetNullAnalyticsData
     */
    private function getGoogleAnalyticsData()
    {
        try {
            return app(GetGoogleAnalyticsData::class);
        } catch (Exception $e) {
            // don't pollute logs with useless errors if
            // user did not set up google analytics yet
            if (Str::contains($e->getMessage(), "Can't find the .p12 certificate")) {
                return new GetNullAnalyticsData();
            } else {
                throw($e);
            }
        }
    }
}
