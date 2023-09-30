<?php

namespace App\Actions\Company;

use App\Models\Company;

class ProcessCountriesAction
{
    /**
     * Process countries into company.
     *
     * @param Company $company
     * @param $countries
     */
    public function execute(Company $company, $countries): void
    {
        self::processCountries($company, $countries);
    }

    // ******************
    //  Static Methods
    // ******************

    private static function processCountries(Company $company, $countries): void
    {
        $arr = [];

        if ($countries && count($countries)) {
            foreach ($countries as $country) {
                $arr[$country['country_id']] = [
                    'discount' => $country['discount'],
                    'increase' => $country['increase']
                ];
            }
        }

        $company->countries()->sync($arr);
    }
}
