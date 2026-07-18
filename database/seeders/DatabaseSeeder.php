<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\ChurchService;
use App\Models\QrCode;
use App\Models\Visitor;
use App\Models\VisitorAssignment;
use App\Models\VisitorNote;
use App\Models\VisitorRegistration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default church for MVP — do not hardcode names elsewhere; use this seeded church
        $church = Church::query()->firstOrCreate(
            ['slug' => 'loveworld-arena-ph-zone-2'],
            [
                'name' => 'Loveworld Arena PH zone 2',
                'code' => 'LA-PH-Z2',
                'email' => 'info@loveworld-arena-ph.test',
                'phone' => '+234 700 000 0000',
                'address' => 'Loveworld Arena, Zone 2',
                'city' => 'Port Harcourt',
                'state' => 'Rivers State',
                'country' => 'Nigeria',
                'status' => 'active',
            ]
        );

        QrCode::query()->firstOrCreate(
            ['code' => 'welcome-service'],
            [
                'church_id' => $church->id,
                'label' => 'Main Entrance Welcome QR',
                'status' => 'active',
            ]
        );

        $user = \App\Models\User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $samples = [
            ['Ada', 'Okafor', 'female', '+234 801 111 1111', 'Product Designer', true, true, 'Please pray for clarity in my career decisions.'],
            ['Daniel', 'Adebayo', 'male', '+234 802 222 2222', 'Software Engineer', false, true, null],
            ['Mariam', 'Balogun', 'female', '+234 803 333 3333', 'Teacher', true, false, 'Pray for my family and healing for my mother.'],
            ['Joshua', 'Eze', 'male', '+234 804 444 4444', 'Business Owner', true, true, 'I need direction for a new business opportunity.'],
            ['Tolu', 'Williams', 'female', '+234 805 555 5555', 'Student', false, false, null],
            ['Grace', 'Nwosu', 'female', '+234 806 666 6666', 'Nurse', true, true, 'Pray for strength and peace.'],
            ['Samuel', 'Ibrahim', 'male', '+234 807 777 7777', 'Architect', false, false, null],
            ['Ruth', 'Johnson', 'female', '+234 808 888 8888', 'Accountant', true, false, 'I am trusting God for a new job.'],
        ];

        foreach ($samples as $index => [$firstName, $lastName, $gender, $phone, $occupation, $bornAgain, $membership, $prayerRequest]) {
            $visitor = Visitor::query()->firstOrCreate(
                ['church_id' => $church->id, 'phone' => $phone],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $gender,
                    'date_of_birth' => now()->subYears(18 + ($index * 4))->subDays($index * 11)->toDateString(),
                    'email' => strtolower($firstName.'.'.$lastName.'@example.test'),
                    'address' => ($index + 10).' Covenant Street, Lagos',
                    'nearest_bus_stop' => ['Ikeja Along', 'Berger', 'Yaba', 'Lekki Phase 1'][$index % 4],
                    'occupation' => $occupation,
                    'invited_by' => ['Pastor James', 'Sister Faith', 'Brother John', null][$index % 4],
                    'born_again' => $bornAgain,
                    'wants_membership' => $membership,
                    'status' => 'new',
                ]
            );

            $registeredAt = now()->subDays($index * 5)->setTime(9 + ($index % 4), 15);

            VisitorRegistration::query()->firstOrCreate(
                [
                    'visitor_id' => $visitor->id,
                    'registered_on' => $registeredAt->toDateString(),
                ],
                [
                    'public_uuid' => (string) Str::uuid(),
                    'church_id' => $church->id,
                    'qr_code_id' => QrCode::query()->where('code', 'welcome-service')->value('id'),
                    'prayer_request' => $prayerRequest,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                    'created_at' => $registeredAt,
                    'updated_at' => $registeredAt,
                ]
            );

            if ($index < 5) {
                VisitorAssignment::query()->firstOrCreate(
                    ['visitor_id' => $visitor->id, 'assigned_at' => $registeredAt->copy()->addHour()],
                    [
                        'church_id' => $church->id,
                        'assigned_to' => $user->id,
                        'assigned_by' => $user->id,
                        'status' => $index % 2 === 0 ? 'pending' : 'completed',
                        'priority' => $membership ? 'high' : 'normal',
                        'notes' => 'Follow up after Sunday service.',
                        'completed_at' => $index % 2 === 0 ? null : $registeredAt->copy()->addDays(1),
                    ]
                );
            }

            if ($index < 6) {
                VisitorNote::query()->firstOrCreate(
                    ['visitor_id' => $visitor->id, 'body' => 'Initial welcome team note for '.$firstName.'.'],
                    [
                        'church_id' => $church->id,
                        'user_id' => $user->id,
                        'type' => $index % 2 === 0 ? 'care' : 'follow-up',
                    ]
                );
            }
        }

        // Seed SMS templates
        $this->call([\Database\Seeders\SmsTemplatesSeeder::class]);
    }
}
