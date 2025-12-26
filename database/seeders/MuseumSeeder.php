<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Museum;

class MuseumSeeder extends Seeder
{
    public function run(): void
    {
        $museums = [
            [
                'name_ru' => 'Национальный музей Рима',
                'name_original' => 'Museo Nazionale Romano',
                'description' => 'Крупнейшее собрание древнеримского искусства и археологических памятников.',
                'detailed_description' => "Крупнейшее собрание древнеримского искусства Палаццо Массимо, представленное в четырёх зданиях, из которых центральная часть коллекции экспонируется в здании Палаццо Массимо, в котором представлены скульптуры Дискобола, скульптурные портреты римских императоров и саркофаги.\n\nЧасть постоянно пополняемых коллекций выставлена в Термах Диоклетиана, Палаццо Альтемпс и в музее средневекового Рима Крипта Бальби.",
                'address' => 'Via Sant\'Apollinare 46 - 00186 Roma',
                'working_hours' => '9:00-19:00 (вт-вс)',
                'ticket_price' => 10.00,
                'website_url' => 'https://museonazionaleromano.beniculturali.it/',
                'image_filename' => 'museum_1.jpg'
            ],
            [
                'name_ru' => 'Капитолийский музей',
                'name_original' => 'Musei Capitolini',
                'description' => 'Музей, где хранятся остатки капитолийского колосса.',
                'detailed_description' => "История музеев начинается с 1471 году, когда папа римский Сикст IV принёс в дар жителям Рима коллекцию бронзовых статуй, которые до этого хранились в Латеране.\n\nКоллекция музея размещена в двух из трёх зданиях на площади Капитолийского холма (Piazza del Campidoglio) — Palazzo dei Conservatori и Palazzo Nuovo.",
                'address' => 'Piazza del Campidoglio, 1 Roma',
                'working_hours' => '9:30-19:30 (ежедневно)',
                'ticket_price' => 15.00,
                'website_url' => 'https://museicapitolini.org/',
                'image_filename' => 'museum_2.jpg'
            ],
            [
                'name_ru' => 'Центр Монтемартини',
                'name_original' => 'Centrale Montemartini',
                'description' => 'Коллекция классической скульптуры из раскопок XIX-XX веков.',
                'detailed_description' => "Музей расположен на левом берегу Тибра. Первоначально в здании располагалась первая в городе электростанция.\n\nВ музее наличествуют «колонный» зал с экспонатами эпохи римской Республики, «машинный» зал.",
                'address' => 'Via Ostiense, 106, 00154 Roma',
                'working_hours' => '9:00-19:00 (вт-вс)',
                'ticket_price' => 7.50,
                'website_url' => 'https://centralemontemartini.org/',
                'image_filename' => 'museum_3.jpg'
            ],
            [
                'name_ru' => 'Ватиканская пинакотека',
                'name_original' => 'Pinacoteca Vaticana',
                'description' => 'Картинная галерея, входящая в состав музеев Ватикана.',
                'detailed_description' => "Ватиканская пинакотека была основана папой Пием VI во второй половине XVIII века.\n\nВо время французской оккупации в 1797 году многие из картин были отправлены по приказу Наполеона Бонапарта в Париж.",
                'address' => 'Ватикан',
                'working_hours' => '9:00-18:00 (пн-сб)',
                'ticket_price' => 17.00,
                'website_url' => 'https://mv.vatican.va/2_IT/pages/PIN/PIN_Main.html',
                'image_filename' => 'museum_4.jpg'
            ],
            [
                'name_ru' => 'Григорианский Египетский музей',
                'name_original' => 'Museo Gregoriano Egizio',
                'description' => 'В музее располагается небольшое собрание предметов искусства египетских династий c III тысячелетия.',
                'detailed_description' => "Музей основан в 1839 году папой Григорием XVI, однако первая коллекция была собрана ещё при папе Пие VII.\n\nВ музее располагается небольшое собрание предметов искусства египетских династий c III тысячелетия.",
                'address' => 'Ватикан',
                'working_hours' => '9:00-18:00 (пн-сб)',
                'ticket_price' => 17.00,
                'website_url' => 'https://museivaticani.va/content/museivaticani/it/collezioni/musei/museo-gregoriano-egizio.html',
                'image_filename' => 'museum_5.jpg'
            ]
        ];

        foreach ($museums as $museumData) {
            $museum = Museum::create($museumData);
        }
    }
}