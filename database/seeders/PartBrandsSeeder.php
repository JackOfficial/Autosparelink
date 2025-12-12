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
        $brands = [
            "Replacement OEM", "555", "888", "Acura", "Ac Delco", "Aisin", "Allied Nippon",
            "Anchor", "Aquil Star", "Asimco", "Asso", "Ate", "Atek", "Bando", "Bbt", "Behr",
            "Bendu", "Bentley", "Beral", "Beru", "Besf1Ts", "Bilstein", "Blackbelt", "Bock",
            "Bomag", "Bosal", "Bosch", "Boss", "Bremi", "Bw", "Camellia", "Century", "Clifford",
            "Compak", "Contitech", "Corteco", "Crosland", "Ctr", "Cummins", "Daido",
            "Daikin-Exedy", "Denki", "Denso", "Depo", "Detroit Diesel", "Diesel Technic",
            "Dokuro", "Dph", "Dtp", "Elgrin", "Elring", "Eristic", "Etg", "Eurotech G",
            "Euro Tech", "Exedy", "Fag", "Fbk", "Febest", "Febi", "Fic", "Fifft", "Filtron",
            "Flag", "Fleet Guard", "Flosser", "Forch", "Fram", "Fte", "Fujiayma", "Fyh", "Gk",
            "Glyco", "Gmb", "Goetze", "Gsp", "Hansa", "Hasaki", "Hdk", "Hd Coil Springs",
            "Hella", "Hengst", "Hkt", "Hyundai", "Icer", "Iljin", "Iwis", "Izumi", "Jag", "Jeep",
            "Jfbk", "Jpc", "Jp Group", "Js Asakashi", "Kashiyama", "Kashiyama Blue", "Kayaba",
            "Kg", "Knecht", "Koito", "Kolbenschmidt", "Komatsu", "Koyo", "Ks", "Ksm", "Kyb",
            "Kyosan", "Lemforder", "Magneti Marelli", "Mahle", "Maxpart", "Mbs", "Mercedes-Benz",
            "Mitsu", "Mitsuboshi", "Mobil", "Mobis", "Mrk", "Musashi", "Nachi", "Nakamoto",
            "Napco", "Ndc", "New Era", "Ngk", "Nis", "Nissens", "Nkk", "Nkn", "Nok", "Npr",
            "Npw", "Nsk", "Ntn", "Onnuri", "Osk", "Paraut", "Pluto", "Rbi", "Rik", "Riken",
            "Rocky", "Roulunds", "Sabah", "Sachs", "Sam", "Sankei", "Sbk", "Seiken", "Seiwa",
            "Sh", "Simer", "Skf", "Ssangyong", "Stone", "Subaki", "Sun", "Taiho", "Tama",
            "Tayen", "Teikin", "Teikoku Piston", "Textar", "Timken", "Tkd", "Toa", "Tokico",
            "Top", "Top Drive", "Toyo", "Trifa", "Trucktec", "Trw", "Tsk", "Tyc", "Tyg", "Tzk",
            "Urw", "Vaico", "Valeo", "Vic", "Volvo Heavy", "Winbo", "Wix Filters", "Wrb",
            "Yamaha", "Yec"
        ];

        foreach ($brands as $brand) {
            DB::table('part_brands')->insert([
                'name' => $brand,
                'type' => 'Aftermarket', // default type
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
