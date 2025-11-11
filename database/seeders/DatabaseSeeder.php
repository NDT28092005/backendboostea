<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\GiftCategory;
use App\Models\GiftPackage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản admin
        $admin = User::create([
            'name' => 'Admin GiftMaster',
            'email' => 'admin@giftmaster.com',
            'password' => Hash::make('admin123'),
            'phone' => '0123456789',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'student_id' => null,
            'university' => null,
            'address' => '123 Đường ABC, Quận XYZ, TP.HCM',
            'date_of_birth' => '1990-01-01',
            'is_student_verified' => false,
        ]);

        // Tạo tài khoản sinh viên mẫu
        $student = User::create([
            'name' => 'Nguyễn Văn Sinh Viên',
            'email' => 'student@example.com',
            'password' => Hash::make('student123'),
            'phone' => '0987654321',
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
            'student_id' => 'SV2024001',
            'university' => 'Đại học ABC',
            'address' => '456 Đường DEF, Quận GHI, TP.HCM',
            'date_of_birth' => '2000-05-15',
            'is_student_verified' => true,
        ]);

        // Tạo danh mục gói quà
        $categories = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà Sinh Nhật',
                'code' => 'BIRTHDAY',
                'description' => 'Gói quà đặc biệt cho sinh nhật',
                'icon_url' => '/images/categories/birthday.png',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà Valentine',
                'code' => 'VALENTINE',
                'description' => 'Gói quà lãng mạn cho ngày Valentine',
                'icon_url' => '/images/categories/valentine.png',
                    'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà Tốt Nghiệp',
                'code' => 'GRADUATION',
                'description' => 'Chúc mừng tốt nghiệp',
                'icon_url' => '/images/categories/graduation.png',
                    'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà Noel',
                'code' => 'CHRISTMAS',
                'description' => 'Combo Giáng sinh',
                'icon_url' => '/images/categories/christmas.png',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà 20/11',
                'code' => 'TEACHERS_DAY',
                'description' => 'Tri ân thầy cô',
                'icon_url' => '/images/categories/teachers.png',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà Tết',
                'code' => 'NEW_YEAR',
                'description' => 'Combo năm mới',
                'icon_url' => '/images/categories/newyear.png',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà 8/3',
                'code' => 'WOMENS_DAY',
                'description' => 'Quà tặng phụ nữ',
                'icon_url' => '/images/categories/womens.png',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Quà Halloween',
                'code' => 'HALLOWEEN',
                'description' => 'Combo Halloween',
                'icon_url' => '/images/categories/halloween.png',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            GiftCategory::create($category);
        }

        // Tạo gói quà mẫu
        $giftPackages = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Gói Quà Sinh Nhật Đặc Biệt',
                'slug' => 'goi-qua-sinh-nhat-dac-biet',
                'description' => 'Gói quà sinh nhật hoàn hảo với bánh kem, hoa tươi và quà tặng ý nghĩa. Phù hợp cho mọi lứa tuổi.',
                'price' => 299000,
                'original_price' => 399000,
                'discount_percentage' => 25,
                'image_url' => '/images/gifts/birthday-special.jpg',
                'banner_url' => '/images/gifts/birthday-special-banner.jpg',
                'status' => 'active',
                'is_featured' => true,
                'is_student_discount' => true,
                'category_id' => $categories[0]['id'],
                'target_audience' => 'Mọi lứa tuổi',
                'delivery_time' => '1-2 ngày',
                'warranty_period' => 7,
                'rating' => 4.8,
                'review_count' => 156,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Combo Quà Tặng Valentine',
                'slug' => 'combo-qua-tang-valentine',
                'description' => 'Gói quà lãng mạn cho ngày Valentine với hoa hồng, chocolate và quà tặng đặc biệt.',
                'price' => 199000,
                'original_price' => 299000,
                'discount_percentage' => 33,
                'image_url' => '/images/gifts/valentine-combo.jpg',
                'banner_url' => '/images/gifts/valentine-combo-banner.jpg',
                'status' => 'active',
                'is_featured' => true,
                'is_student_discount' => true,
                'category_id' => $categories[1]['id'],
                'target_audience' => 'Cặp đôi',
                'delivery_time' => '1 ngày',
                'warranty_period' => 5,
                'rating' => 4.9,
                'review_count' => 89,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Gói Quà Tốt Nghiệp',
                'slug' => 'goi-qua-tot-nghiep',
                'description' => 'Chúc mừng tốt nghiệp với gói quà ý nghĩa bao gồm hoa, quà tặng và lời chúc.',
                'price' => 399000,
                'original_price' => 499000,
                'discount_percentage' => 20,
                'image_url' => '/images/gifts/graduation-gift.jpg',
                'banner_url' => '/images/gifts/graduation-gift-banner.jpg',
                'status' => 'active',
                'is_featured' => false,
                'is_student_discount' => true,
                'category_id' => $categories[2]['id'],
                'target_audience' => 'Sinh viên tốt nghiệp',
                'delivery_time' => '2-3 ngày',
                'warranty_period' => 7,
                'rating' => 4.7,
                'review_count' => 45,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Combo Quà Noel',
                'slug' => 'combo-qua-noel',
                'description' => 'Gói quà Giáng sinh đặc biệt với cây thông mini, quà tặng và thiệp chúc mừng.',
                'price' => 249000,
                'original_price' => 349000,
                'discount_percentage' => 29,
                'image_url' => '/images/gifts/christmas-combo.jpg',
                'banner_url' => '/images/gifts/christmas-combo-banner.jpg',
                'status' => 'active',
                'is_featured' => true,
                'is_student_discount' => false,
                'category_id' => $categories[3]['id'],
                'target_audience' => 'Gia đình, trẻ em',
                'delivery_time' => '1-2 ngày',
                'warranty_period' => 10,
                'rating' => 4.6,
                'review_count' => 78,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Gói Quà 20/11',
                'slug' => 'goi-qua-20-11',
                'description' => 'Tri ân thầy cô với gói quà ý nghĩa bao gồm hoa, quà tặng và lời chúc.',
                'price' => 179000,
                'original_price' => 279000,
                'discount_percentage' => 36,
                'image_url' => '/images/gifts/teachers-day.jpg',
                'banner_url' => '/images/gifts/teachers-day-banner.jpg',
                'status' => 'active',
                'is_featured' => false,
                'is_student_discount' => true,
                'category_id' => $categories[4]['id'],
                'target_audience' => 'Thầy cô giáo',
                'delivery_time' => '1-2 ngày',
                'warranty_period' => 7,
                'rating' => 4.8,
                'review_count' => 123,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Combo Quà Tết',
                'slug' => 'combo-qua-tet',
                'description' => 'Gói quà Tết truyền thống với bánh kẹo, hoa và quà tặng may mắn.',
                'price' => 599000,
                'original_price' => 799000,
                'discount_percentage' => 25,
                'image_url' => '/images/gifts/new-year-combo.jpg',
                'banner_url' => '/images/gifts/new-year-combo-banner.jpg',
                'status' => 'active',
                'is_featured' => true,
                'is_student_discount' => false,
                'category_id' => $categories[5]['id'],
                'target_audience' => 'Gia đình',
                'delivery_time' => '3-5 ngày',
                'warranty_period' => 15,
                'rating' => 4.9,
                'review_count' => 234,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Gói Quà 8/3',
                'slug' => 'goi-qua-8-3',
                'description' => 'Quà tặng đặc biệt cho phụ nữ nhân ngày 8/3 với hoa, quà tặng và lời chúc.',
                'price' => 199000,
                'original_price' => 299000,
                'discount_percentage' => 33,
                'image_url' => '/images/gifts/womens-day.jpg',
                'banner_url' => '/images/gifts/womens-day-banner.jpg',
                'status' => 'active',
                'is_featured' => false,
                'is_student_discount' => true,
                'category_id' => $categories[6]['id'],
                'target_audience' => 'Phụ nữ',
                'delivery_time' => '1 ngày',
                'warranty_period' => 7,
                'rating' => 4.7,
                'review_count' => 67,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'Combo Quà Halloween',
                'slug' => 'combo-qua-halloween',
                'description' => 'Gói quà Halloween vui nhộn với trang phục, kẹo và đồ chơi.',
                'price' => 149000,
                'original_price' => 249000,
                'discount_percentage' => 40,
                'image_url' => '/images/gifts/halloween-combo.jpg',
                'banner_url' => '/images/gifts/halloween-combo-banner.jpg',
                'status' => 'active',
                'is_featured' => false,
                'is_student_discount' => true,
                'category_id' => $categories[7]['id'],
                'target_audience' => 'Trẻ em, thanh thiếu niên',
                'delivery_time' => '2-3 ngày',
                'warranty_period' => 5,
                'rating' => 4.5,
                'review_count' => 34,
            ],
        ];

        foreach ($giftPackages as $giftPackage) {
            GiftPackage::create($giftPackage);
        }

        // Chạy seeder cho cài đặt hệ thống
        $this->call(SystemSettingsSeeder::class);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin account: admin@giftmaster.com / admin123');
        $this->command->info('Student account: student@example.com / student123');
    }
}