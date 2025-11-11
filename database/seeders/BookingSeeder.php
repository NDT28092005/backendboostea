<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        // ====== SEAT TYPES ======
        $seatTypes = [
            ['code' => 'STD', 'name' => 'Standard', 'price_modifier' => 0, 'color_code' => '#00FF00'],
            ['code' => 'VIP', 'name' => 'VIP', 'price_modifier' => 50000, 'color_code' => '#FFD700'],
            ['code' => 'COU', 'name' => 'Couple', 'price_modifier' => 70000, 'color_code' => '#FF69B4'],
        ];

        foreach ($seatTypes as $st) {
            DB::table('seat_types')->updateOrInsert(
                ['code' => $st['code']],
                [
                    'id' => DB::table('seat_types')->where('code', $st['code'])->value('id') ?? Str::uuid(),
                    'name' => $st['name'],
                    'price_modifier' => $st['price_modifier'],
                    'color_code' => $st['color_code'],
                    'created_at' => now(),
                    // 'updated_at' => now(),
                ]
            );
        }

        // ====== TICKET TYPES ======
        $ticketTypes = [
            ['code' => 'ADULT', 'name' => 'Adult', 'base_price' => 120000, 'refundable' => true],
            ['code' => 'CHILD', 'name' => 'Child', 'base_price' => 80000, 'refundable' => false],
            ['code' => 'SENIOR', 'name' => 'Senior', 'base_price' => 90000, 'refundable' => true],
        ];

        foreach ($ticketTypes as $tt) {
            DB::table('ticket_types')->updateOrInsert(
                ['code' => $tt['code']],
                [
                    'id' => DB::table('ticket_types')->where('code', $tt['code'])->value('id') ?? Str::uuid(),
                    'name' => $tt['name'],
                    'base_price' => $tt['base_price'],
                    'refundable' => $tt['refundable'],
                    'created_at' => now(),
                    // 'updated_at' => now(),
                ]
            );
        }

        // ====== SEATS ======
        $auditoriums = DB::table('auditoriums')->get();
        $seatTypesDb = DB::table('seat_types')->pluck('id', 'code');

        foreach ($auditoriums as $aud) {
            $rows = range('A', chr(64 + $aud->row_count));
            $cols = range(1, $aud->col_count);

            foreach ($rows as $row) {
                foreach ($cols as $col) {
                    $seatLabel = $row . $col;
                    $isVip = ($row <= 'B'); // Hàng A, B là VIP
                    $seatTypeId = $isVip ? $seatTypesDb['VIP'] : $seatTypesDb['STD'];

                    DB::table('seats')->updateOrInsert(
                        ['auditorium_id' => $aud->id, 'seat_label' => $seatLabel],
                        [
                            'id' => DB::table('seats')->where([
                                'auditorium_id' => $aud->id,
                                'seat_label' => $seatLabel
                            ])->value('id') ?? Str::uuid(),
                            'row_label' => $row,
                            'col_index' => $col,
                            'seat_type_id' => $seatTypeId,
                            'is_active' => true,
                            'is_vip' => $isVip,
                            'created_at' => now(),
                            // 'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        // ====== SAMPLE BOOKING ======
        $adminUser = DB::table('users')->where('email', 'admin@cinema.local')->value('id');
        $showtimeId = DB::table('showtimes')->inRandomOrder()->value('id');

        $bookingCode = $this->generateBookingCode();

        $bookingId = Str::uuid();
        DB::table('bookings')->insert([
            'id' => $bookingId,
            'booking_code' => $bookingCode,
            'user_id' => $adminUser,
            'showtime_id' => $showtimeId,
            'total_amount' => 360000,
            'discount_amount' => 0,
            'final_amount' => 360000,
            'payment_due' => now()->addHour(),
            'status' => 'pending',
            'payment_method' => 'Cash',
            'notes' => 'Test booking',
            'created_at' => now(),
            // 'updated_at' => now(),
        ]);

        // ====== BOOKED SEATS ======
        $ticketTypesDb = DB::table('ticket_types')->pluck('id', 'code');
        $seats = DB::table('seats')->where('auditorium_id', $auditoriums->first()->id)->limit(3)->get();

        foreach ($seats as $seat) {
            DB::table('booked_seats')->insert([
                'id' => Str::uuid(),
                'booking_id' => $bookingId,
                'seat_id' => $seat->id,
                'seat_label_snapshot' => $seat->seat_label,
                'seat_type_id' => $seat->seat_type_id,
                'ticket_type_id' => $ticketTypesDb['ADULT'],
                'unit_price' => 120000,
                'discount_amount' => 0,
                'final_price' => 120000,
                'ticket_code' => 'TKT' . rand(1000, 9999),
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Generate unique booking code
     */
    private function generateBookingCode()
    {
        do {
            $code = 'BKG' . rand(1000, 9999);
            $exists = DB::table('bookings')->where('booking_code', $code)->exists();
        } while ($exists);

        return $code;
    }
}