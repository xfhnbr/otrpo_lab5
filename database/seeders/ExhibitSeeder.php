<?php

namespace Database\Seeders;

use App\Models\Exhibit;
use App\Models\Museum;
use Illuminate\Database\Seeder;

class ExhibitSeeder extends Seeder
{
    public function run(): void
    {
        $exhibits = [
            [
                'name' => 'Статуя Дискобола',
                'image_filename' => 'exhibit_1.jpg',
                'museum_name' => 'Национальный музей Рима',
            ],
            [
                'name' => 'Капитолийская волчица',
                'image_filename' => 'exhibit_2.jpg',
                'museum_name' => 'Капитолийский музей',
            ],
            [
                'name' => 'Саркофаг супругов',
                'image_filename' => 'exhibit_3.jpg',
                'museum_name' => 'Центр Монтемартини',
            ],
            [
                'name' => 'Леонардо да Винчи. Св. Иероним. 1480. Холст на дереве, темпера',
                'image_filename' => 'exhibit_4.jpg',
                'museum_name' => 'Ватиканская пинакотека',
            ],
            [
                'name' => 'Статуя Уджагорресента',
                'image_filename' => 'exhibit_5.jpg',
                'museum_name' => 'Григорианский Египетский музей',
            ],
        ];

        foreach ($exhibits as $exhibitData) {
            $museum = Museum::where('name_ru', $exhibitData['museum_name'])->first();
            
            Exhibit::create([
                'name' => $exhibitData['name'],
                'image_filename' => $exhibitData['image_filename'],
                'museum_id' => $museum->id,
                'user_id' => $museum->user_id,
            ]);
        }
    }
}