<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartBrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------------
        // OEM BRANDS (Original Manufacturer)
        // -------------------------------
        $oemBrands = [
            "Aisin",
            "Denso",
            "Bosch",
            "NGK",
            "NTK",
            "Delphi",
            "Magneti Marelli",
            "Hella",
            "Mahle",
            "Mann Filter",
            "ZF",
            "Lemforder",
            "Bilstein",
            "Brembo",
            "Akebono",
            "Gates",
            "Contitech",
            "Koito",
            "NSK",
            "NTN",
            "NPR",
            "NipponDenso",
            "Hitachi",
            "KYB",
        ];

        // -------------------------------
        // AFTERMARKET / REPLACEMENT BRANDS
        // -------------------------------
        $aftermarketBrands = [
            "Replacement OEM", "555", "888", "Acura", "Ac Delco", "Allied Nippon",
            "Anchor", "Aquil Star", "Asimco", "Asso", "Ate", "Atek", "Bando", "Bbt", "Behr",
            "Bendu", "Bentley", "Beral", "Beru", "Besf1Ts", "Blackbelt", "Bock",
            "Bomag", "Bosal", "Boss", "Bremi", "Bw", "Camellia", "Century", "Clifford",
            "Compak", "Corteco", "Crosland", "Ctr", "Cummins", "Daido",
            "Daikin-Exedy", "Denki", "Depo", "Detroit Diesel", "Diesel Technic",
            "Dokuro", "Dph", "Dtp", "Elgrin", "Elring", "Eristic", "Etg", "Eurotech G",
            "Euro Tech", "Exedy", "Fag", "Fbk", "Febest", "Febi", "Fic", "Fifft", "Filtron",
            "Flag", "Fleet Guard", "Flosser", "Forch", "Fram", "Fte", "Fujiayma", "Fyh", "Gk",
            "Glyco", "Gmb", "Goetze", "Gsp", "Hansa", "Hasaki", "Hdk", "Hd Coil Springs",
            "Hkt", "Hyundai", "Icer", "Iljin", "Iwis", "Izumi", "Jag", "Jeep",
            "Jfbk", "Jpc", "Jp Group", "Js Asakashi", "Kashiyama", "Kashiyama Blue", "Kayaba",
            "Kg", "Knecht", "Komatsu", "Koyo", "Ks", "Ksm",
            "Kyosan", "Maxpart", "Mbs", "Mercedes-Benz",
            "Mitsu", "Mitsuboshi", "Mobil", "Mobis", "Mrk", "Musashi", "Nachi", "Nakamoto",
            "Napco", "Ndc", "New Era", "Nis", "Nissens", "Nkk", "Nkn", "Nok",
            "Npw", "Ntn", "Onnuri", "Osk", "Paraut", "Pluto", "Rbi", "Rik", "Riken",
            "Rocky", "Roulunds", "Sabah", "Sachs", "Sam", "Sankei", "Sbk", "Seiken", "Seiwa",
            "Sh", "Simer", "Skf", "Ssangyong", "Stone", "Subaki", "Sun", "Taiho", "Tama",
            "Tayen", "Teikin", "Teikoku Piston", "Textar", "Timken", "Tkd", "Toa", "Tokico",
            "Top", "Top Drive", "Toyo", "Trifa", "Trucktec", "Trw", "Tsk", "Tyc", "Tyg", "Tzk",
            "Urw", "Vaico", "Valeo", "Vic", "Volvo Heavy", "Winbo", "Wix Filters", "Wrb",
            "Yamaha", "Yec"
        ];

        // Insert OEM brands
        foreach ($oemBrands as $brand) {
            DB::table('part_brands')->insert([
                'name' => $brand,
                'type' => 'OEM',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Aftermarket brands
        foreach ($aftermarketBrands as $brand) {
            DB::table('part_brands')->insert([
                'name' => $brand,
                'type' => 'Aftermarket',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
