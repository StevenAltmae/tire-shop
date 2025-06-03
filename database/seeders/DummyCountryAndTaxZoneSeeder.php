<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyCountryAndTaxZoneSeeder extends Seeder
{
    public function run()
    {
        // Delete existing dummy data to avoid foreign key constraint errors
        DB::table('lunar_tax_zone_countries')->where('country_id', 1)->delete();
        DB::table('lunar_cart_addresses')->where('country_id', 1)->delete();
        DB::table('lunar_countries')->where('id', 1)->delete();
        DB::table('lunar_tax_zones')->where('id', 1)->delete();

        // Insert a dummy country
        DB::table('lunar_countries')->insert([
            'id' => 1,
            'name' => 'Dummy Country',
            'iso3' => 'DUM',
            'iso2' => 'DM',
            'phonecode' => '000',
            'currency' => 'DUM',
            'emoji' => '🏳️',
            'emoji_u' => 'U+1F3F3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert a dummy tax zone
        DB::table('lunar_tax_zones')->insert([
            'id' => 1,
            'name' => 'Dummy Tax Zone',
            'zone_type' => 'country',
            'price_display' => 'included',
            'active' => 1,
            'default' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Link the country to the tax zone
        DB::table('lunar_tax_zone_countries')->insert([
            'tax_zone_id' => 1,
            'country_id' => 1,
        ]);
    }
} 