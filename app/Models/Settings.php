<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Settings extends Model
{
    protected $fillable = [
        'slug',
        'label',
        'value',
    ];

    public static function getCompanyInformations()
    {
        return Settings::where('slug', 'like', '%company_%')->get()->keyBy('slug');
    }

    public static function getTaxCustomSettings()
    {
        return Settings::where('slug', 'like', '%tax_custom_%')->get()->keyBy('slug');
    }

    public static function getCompanyCustomSettings()
    {
        return Settings::where('slug', 'like', '%company_custom_%')->where('label', '!=', '')->where('value', '!=', '')->get()->keyBy('slug');
    }

    public static function getCompanyFinancialYear()
    {
        $companyFinancialSettings = [];
        $finYear = Settings::where('slug', 'like', 'company_financial_year%')->get()->keyBy('slug');
        if (isset($finYear['company_financial_year_from']) &&
           isset($finYear['company_financial_year_to']) &&
           $finYear['company_financial_year_from']->value != '' &&
           $finYear['company_financial_year_to']->value != '') {
            $companyFinancialSettings['start']['daymonth'] = $finYear['company_financial_year_from']['value'];
            $companyFinancialSettings['start']['day'] = explode('/', $finYear['company_financial_year_from']['value'])[0];
            $companyFinancialSettings['start']['month'] = explode('/', $finYear['company_financial_year_from']['value'])[1];
            $companyFinancialSettings['end']['daymonth'] = $finYear['company_financial_year_to']['value'];
            $companyFinancialSettings['end']['day'] = explode('/', $finYear['company_financial_year_to']['value'])[0];
            $companyFinancialSettings['end']['month'] = explode('/', $finYear['company_financial_year_to']['value'])[1];
            $companyFinancialSettings['start']['date'] = date('Y').'/'.$finYear['company_financial_year_from']['value'];
            $companyFinancialSettings['end']['date'] = date('Y').'/'.$finYear['company_financial_year_to']['value'];
        }

        return $companyFinancialSettings;
    }

    public static function getFinancialCustomSettings()
    {
        return Settings::where('slug', 'financial_custom')->where('label', '!=', '')->get()->keyBy('slug');
    }

    public static function getEmailConfigSettings()
    {
        return Settings::where('slug', 'like', 'email_config%')->where('slug', 'not like', 'email_config_general%')->where('label', '!=', '')->select('label', 'slug', 'value')->get()->mapWithKeys(function ($item) {
            $key = Str::replace('email_config_', '', $item['slug']);

            return [$key => $item];
        });
    }

    public static function getEmailGeneralSettings()
    {
        return Settings::where('slug', 'like', 'email_config_general%')->where('label', '!=', '')
            ->select('label', 'slug', 'value')->get()->mapWithKeys(function ($item) {
                $key = Str::replace('email_config_', '', $item['slug']);

                return [$key => $item];
            });
    }

    public static function getCurrentFinancialYear()
    {
        $finYearSettings = Settings::getCompanyFinancialYear();
        if (Carbon::now()->format('d/m') >= $finYearSettings['start']['daymonth']) {
            $start = Carbon::now()->startOfYear()->addMonths($finYearSettings['end']['month']);
        } else {
            $start = Carbon::now()->startOfYear()->subMonths(12 - $finYearSettings['end']['month']);
        }
        $end = $start->copy()->addYear()->subDay();

        return $start->format('Y').'-'.$end->format('Y');
    }

    public static function getCompanyLogo()
    {
        return Settings::where('slug', '=', 'company_logo')->get();
    }

    public static function getSettings($slug)
    {
        return Settings::where('slug', '=', $slug)->first();
    }

    public static function getProjectSettings()
    {
        return Settings::where('slug', 'like', '%project_%')->get()->keyBy('slug');
    }
}
