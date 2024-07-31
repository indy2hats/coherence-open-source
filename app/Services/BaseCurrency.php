<?php

namespace App\Services;

use App\Models\Taxonomy;
use App\Models\TaxonomyList;

class BaseCurrency implements BaseCurrencyInterface
{
    public function convertCurrency()
    {
        $json = json_decode(file_get_contents('https://api.apilayer.com/exchangerates_data/latest?apikey='.config('api.currency_api_key').'&base='.TaxonomyList::where('taxonomy_id', Taxonomy::where('title', 'Base Currency')->first()->id)->first()->title), true);

        return $json;
    }

    public function getCurrencyRate($currency)
    {
        $json = json_decode(file_get_contents('https://api.apilayer.com/exchangerates_data/latest?apikey='.config('api.currency_api_key').'&base='.$currency), true);

        return $json;
    }

    public function getOldCurrencyRate($currency, $date)
    {
        $json = json_decode(file_get_contents('https://api.apilayer.com/exchangerates_data/'.$date.'?apikey='.config('api.currency_api_key').'&base='.$currency), true);

        return $json;
    }

    public function getBaseCurrency()
    {
        $baseCurrency = TaxonomyList::where('taxonomy_id', Taxonomy::where('title', 'Base Currency')->first()->id)->first()->title;

        return $baseCurrency;
    }
}
