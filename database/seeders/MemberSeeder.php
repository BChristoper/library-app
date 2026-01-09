<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'member_code' => 'M-001',
                'name' => 'Andi Pratama',
                'email' => 'andi@example.test',
                'phone' => '081234567801',
                'address' => 'Jl. Merdeka No. 1',
            ],
            [
                'member_code' => 'M-002',
                'name' => 'Siti Lestari',
                'email' => 'siti@example.test',
                'phone' => '081234567802',
                'address' => 'Jl. Sudirman No. 10',
            ],
            [
                'member_code' => 'M-003',
                'name' => 'Budi Santoso',
                'email' => 'budi@example.test',
                'phone' => '081234567803',
                'address' => 'Jl. Mawar No. 5',
            ],
            [
                'member_code' => 'M-004',
                'name' => 'Rina Kartika',
                'email' => 'rina@example.test',
                'phone' => '081234567804',
                'address' => 'Jl. Melati No. 8',
            ],
            [
                'member_code' => 'M-005',
                'name' => 'Dedi Saputra',
                'email' => 'dedi@example.test',
                'phone' => '081234567805',
                'address' => 'Jl. Kenanga No. 12',
            ],
        ];

        foreach ($members as $member) {
            Member::updateOrCreate(['member_code' => $member['member_code']], $member);
        }
    }
}
