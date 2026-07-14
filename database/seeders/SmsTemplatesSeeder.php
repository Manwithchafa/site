<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsTemplatesSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        SmsTemplate::query()->firstOrCreate(
            ['slug' => 'welcome', 'church_id' => null],
            ['name' => 'Default Welcome', 'body' => 'Hello {{first_name}}, welcome to {{church_name}} — we are glad you joined us today.']
        );
    }
}
