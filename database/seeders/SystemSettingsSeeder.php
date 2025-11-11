<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\Coupon;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cài đặt hệ thống cơ bản
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'GiftMaster',
                'type' => 'string',
                'description' => 'Tên website',
                'is_public' => true
            ],
            [
                'key' => 'site_description',
                'value' => 'Dịch vụ gói quà cho sinh viên',
                'type' => 'string',
                'description' => 'Mô tả website',
                'is_public' => true
            ],
            [
                'key' => 'student_discount_percentage',
                'value' => '10',
                'type' => 'number',
                'description' => 'Phần trăm giảm giá cho sinh viên',
                'is_public' => false
            ],
            [
                'key' => 'free_shipping_threshold',
                'value' => '200000',
                'type' => 'number',
                'description' => 'Ngưỡng miễn phí ship (VND)',
                'is_public' => true
            ],
            [
                'key' => 'default_delivery_time',
                'value' => '1-2 ngày',
                'type' => 'string',
                'description' => 'Thời gian giao hàng mặc định',
                'is_public' => true
            ],
            [
                'key' => 'warranty_period',
                'value' => '7',
                'type' => 'number',
                'description' => 'Thời gian bảo hành (ngày)',
                'is_public' => true
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@giftmaster.com',
                'type' => 'string',
                'description' => 'Email liên hệ',
                'is_public' => true
            ],
            [
                'key' => 'contact_phone',
                'value' => '0123456789',
                'type' => 'string',
                'description' => 'Số điện thoại liên hệ',
                'is_public' => true
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/giftmaster',
                'type' => 'string',
                'description' => 'Link Facebook',
                'is_public' => true
            ],
            [
                'key' => 'zalo_url',
                'value' => 'https://zalo.me/giftmaster',
                'type' => 'string',
                'description' => 'Link Zalo',
                'is_public' => true
            ],
            [
                'key' => 'loyalty_points_rate',
                'value' => '1',
                'type' => 'number',
                'description' => 'Tỷ lệ tích điểm (1 điểm/1000 VND)',
                'is_public' => false
            ],
            [
                'key' => 'bronze_threshold',
                'value' => '0',
                'type' => 'number',
                'description' => 'Ngưỡng thành viên Bronze',
                'is_public' => false
            ],
            [
                'key' => 'silver_threshold',
                'value' => '1000000',
                'type' => 'number',
                'description' => 'Ngưỡng thành viên Silver',
                'is_public' => false
            ],
            [
                'key' => 'gold_threshold',
                'value' => '3000000',
                'type' => 'number',
                'description' => 'Ngưỡng thành viên Gold',
                'is_public' => false
            ],
            [
                'key' => 'platinum_threshold',
                'value' => '5000000',
                'type' => 'number',
                'description' => 'Ngưỡng thành viên Platinum',
                'is_public' => false
            ]
        ];

        foreach ($settings as $setting) {
            SystemSetting::create([
                'id' => \Illuminate\Support\Str::uuid(),
                'setting_key' => $setting['key'],
                'setting_value' => $setting['value'],
                'data_type' => $setting['type'],
                'description' => $setting['description'],
                'is_public' => $setting['is_public']
            ]);
        }

        // Tạo một số coupon mẫu
        $coupons = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách hàng mới',
                'description' => 'Giảm 10% cho đơn hàng đầu tiên',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 100000,
                'maximum_discount' => 50000,
                'usage_limit' => 1000,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
                'applicable_categories' => null
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'code' => 'STUDENT20',
                'name' => 'Ưu đãi sinh viên',
                'description' => 'Giảm 20% cho sinh viên',
                'type' => 'percentage',
                'value' => 20,
                'minimum_amount' => 200000,
                'maximum_discount' => 100000,
                'usage_limit' => 500,
                'user_limit' => 3,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
                'applicable_categories' => null
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'code' => 'FREESHIP',
                'name' => 'Miễn phí ship',
                'description' => 'Miễn phí giao hàng',
                'type' => 'free_shipping',
                'value' => 0,
                'minimum_amount' => 300000,
                'maximum_discount' => null,
                'usage_limit' => 200,
                'user_limit' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
                'applicable_categories' => null
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'code' => 'BIRTHDAY50K',
                'name' => 'Quà sinh nhật',
                'description' => 'Giảm 50k cho đơn hàng sinh nhật',
                'type' => 'fixed_amount',
                'value' => 50000,
                'minimum_amount' => 300000,
                'maximum_discount' => 50000,
                'usage_limit' => 100,
                'user_limit' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(12),
                'is_active' => true,
                'applicable_categories' => ['BIRTHDAY']
            ]
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }

        $this->command->info('System settings and coupons seeded successfully!');
    }
}
