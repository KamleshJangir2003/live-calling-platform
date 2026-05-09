<?php

namespace Database\Seeders;

use App\Models\Call;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\ModelProfile;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────────
        $admin = User::create([
            'name'           => 'Admin',
            'email'          => 'admin@livecall.com',
            'phone'          => '9000000000',
            'password'       => Hash::make('password'),
            'role'           => 'admin',
            'status'         => 'active',
            'phone_verified' => true,
            'wallet_balance' => 0,
            'country'        => 'India',
            'avatar'         => null,
        ]);

        // ─── Test User ────────────────────────────────────────────
        $testUser = User::create([
            'name'           => 'Rahul Kumar',
            'email'          => 'user@livecall.com',
            'phone'          => '9000000001',
            'password'       => Hash::make('password'),
            'role'           => 'user',
            'status'         => 'active',
            'phone_verified' => true,
            'wallet_balance' => 1000.00,
            'country'        => 'India',
            'avatar'         => null,
        ]);

        // ─── 50 Models from different countries ───────────────────
        $models = [
            // India
            ['name'=>'Priya Sharma',    'country'=>'India',       'lang'=>'Hindi,English',       'audio'=>2.0, 'video'=>4.0, 'online'=>true,  'rating'=>4.8, 'calls'=>245, 'bio'=>'Namaste! I love deep conversations and making new friends. Let\'s connect!'],
            ['name'=>'Anjali Singh',    'country'=>'India',       'lang'=>'Hindi,Punjabi',        'audio'=>1.5, 'video'=>3.0, 'online'=>true,  'rating'=>4.6, 'calls'=>189, 'bio'=>'Fun-loving girl from Punjab. I enjoy music, dance and chatting!'],
            ['name'=>'Neha Gupta',      'country'=>'India',       'lang'=>'English,Hindi',        'audio'=>3.0, 'video'=>5.0, 'online'=>false, 'rating'=>4.9, 'calls'=>312, 'bio'=>'Professional model with 3 years experience. Let\'s have quality time!'],
            ['name'=>'Pooja Patel',     'country'=>'India',       'lang'=>'Gujarati,Hindi',       'audio'=>2.0, 'video'=>4.0, 'online'=>true,  'rating'=>4.7, 'calls'=>156, 'bio'=>'Sweet and caring. I love to listen and talk about life!'],
            ['name'=>'Riya Verma',      'country'=>'India',       'lang'=>'Hindi,English',        'audio'=>2.5, 'video'=>5.0, 'online'=>true,  'rating'=>4.5, 'calls'=>98,  'bio'=>'Young and energetic. Always ready for fun conversations!'],
            ['name'=>'Kavya Nair',      'country'=>'India',       'lang'=>'Malayalam,English',    'audio'=>2.0, 'video'=>4.0, 'online'=>false, 'rating'=>4.8, 'calls'=>201, 'bio'=>'From Kerala with love! I speak Malayalam and English fluently.'],
            ['name'=>'Divya Reddy',     'country'=>'India',       'lang'=>'Telugu,English',       'audio'=>1.5, 'video'=>3.5, 'online'=>true,  'rating'=>4.6, 'calls'=>134, 'bio'=>'Telugu girl who loves to chat and make you smile!'],
            ['name'=>'Sneha Joshi',     'country'=>'India',       'lang'=>'Marathi,Hindi',        'audio'=>2.0, 'video'=>4.0, 'online'=>true,  'rating'=>4.7, 'calls'=>178, 'bio'=>'Marathi mulgi! Friendly and warm personality.'],

            // USA
            ['name'=>'Emma Johnson',    'country'=>'USA',         'lang'=>'English',              'audio'=>5.0, 'video'=>8.0, 'online'=>true,  'rating'=>4.9, 'calls'=>423, 'bio'=>'American girl next door. Love talking about life, travel and adventures!'],
            ['name'=>'Sophia Williams', 'country'=>'USA',         'lang'=>'English,Spanish',      'audio'=>4.0, 'video'=>7.0, 'online'=>true,  'rating'=>4.8, 'calls'=>356, 'bio'=>'Bilingual beauty from California. Let\'s have amazing conversations!'],
            ['name'=>'Olivia Brown',    'country'=>'USA',         'lang'=>'English',              'audio'=>5.0, 'video'=>9.0, 'online'=>false, 'rating'=>4.9, 'calls'=>512, 'bio'=>'New York model. Professional, fun and always engaging!'],
            ['name'=>'Ava Davis',       'country'=>'USA',         'lang'=>'English',              'audio'=>4.5, 'video'=>8.0, 'online'=>true,  'rating'=>4.7, 'calls'=>289, 'bio'=>'Texas girl with a big heart. Love country music and deep talks!'],

            // UK
            ['name'=>'Charlotte Smith', 'country'=>'UK',          'lang'=>'English',              'audio'=>4.0, 'video'=>7.0, 'online'=>true,  'rating'=>4.8, 'calls'=>334, 'bio'=>'London girl with a charming accent. Let\'s have a proper chat!'],
            ['name'=>'Amelia Jones',    'country'=>'UK',          'lang'=>'English,French',       'audio'=>4.5, 'video'=>8.0, 'online'=>false, 'rating'=>4.9, 'calls'=>267, 'bio'=>'Oxford educated. Love art, culture and intellectual conversations!'],

            // Russia
            ['name'=>'Anastasia Ivanova','country'=>'Russia',     'lang'=>'Russian,English',      'audio'=>3.0, 'video'=>5.0, 'online'=>true,  'rating'=>4.8, 'calls'=>445, 'bio'=>'Beautiful Russian girl from Moscow. Mysterious and charming!'],
            ['name'=>'Natasha Petrova', 'country'=>'Russia',      'lang'=>'Russian,English',      'audio'=>3.5, 'video'=>6.0, 'online'=>true,  'rating'=>4.7, 'calls'=>378, 'bio'=>'St. Petersburg model. Love ballet, art and deep conversations!'],
            ['name'=>'Katya Sokolova',  'country'=>'Russia',      'lang'=>'Russian,English',      'audio'=>3.0, 'video'=>5.5, 'online'=>false, 'rating'=>4.6, 'calls'=>223, 'bio'=>'Warm Russian soul. Let\'s connect and share stories!'],

            // Brazil
            ['name'=>'Isabella Santos', 'country'=>'Brazil',      'lang'=>'Portuguese,English',   'audio'=>3.0, 'video'=>5.0, 'online'=>true,  'rating'=>4.9, 'calls'=>489, 'bio'=>'Brazilian beauty from Rio! Love samba, beach and fun talks!'],
            ['name'=>'Valentina Costa', 'country'=>'Brazil',      'lang'=>'Portuguese,Spanish',   'audio'=>2.5, 'video'=>4.5, 'online'=>true,  'rating'=>4.7, 'calls'=>312, 'bio'=>'São Paulo girl. Passionate, energetic and always smiling!'],

            // Philippines
            ['name'=>'Maria Santos',    'country'=>'Philippines', 'lang'=>'Filipino,English',     'audio'=>1.5, 'video'=>3.0, 'online'=>true,  'rating'=>4.8, 'calls'=>567, 'bio'=>'Filipina with a warm heart. Love to chat and make friends!'],
            ['name'=>'Angel Reyes',     'country'=>'Philippines', 'lang'=>'Filipino,English',     'audio'=>2.0, 'video'=>3.5, 'online'=>true,  'rating'=>4.9, 'calls'=>634, 'bio'=>'Sweet Filipina from Manila. Always cheerful and friendly!'],
            ['name'=>'Grace Cruz',      'country'=>'Philippines', 'lang'=>'Filipino,English',     'audio'=>1.5, 'video'=>3.0, 'online'=>false, 'rating'=>4.7, 'calls'=>423, 'bio'=>'Cebu girl. Love singing, dancing and making people happy!'],

            // Thailand
            ['name'=>'Nong Thida',      'country'=>'Thailand',    'lang'=>'Thai,English',         'audio'=>2.0, 'video'=>4.0, 'online'=>true,  'rating'=>4.8, 'calls'=>389, 'bio'=>'Thai beauty from Bangkok. Gentle, kind and very friendly!'],
            ['name'=>'Ploy Siriporn',   'country'=>'Thailand',    'lang'=>'Thai,English',         'audio'=>2.5, 'video'=>4.5, 'online'=>true,  'rating'=>4.7, 'calls'=>278, 'bio'=>'Chiang Mai girl. Love Thai culture, food and good conversations!'],

            // Japan
            ['name'=>'Yuki Tanaka',     'country'=>'Japan',       'lang'=>'Japanese,English',     'audio'=>4.0, 'video'=>7.0, 'online'=>true,  'rating'=>4.9, 'calls'=>456, 'bio'=>'Tokyo girl. Kawaii and fun! Love anime, manga and chatting!'],
            ['name'=>'Sakura Yamamoto', 'country'=>'Japan',       'lang'=>'Japanese,English',     'audio'=>4.5, 'video'=>8.0, 'online'=>false, 'rating'=>4.8, 'calls'=>334, 'bio'=>'Osaka beauty. Funny, warm and always entertaining!'],

            // South Korea
            ['name'=>'Ji-Yeon Park',    'country'=>'South Korea', 'lang'=>'Korean,English',       'audio'=>4.0, 'video'=>7.0, 'online'=>true,  'rating'=>4.9, 'calls'=>512, 'bio'=>'Seoul girl. K-pop lover, fashionista and great conversationalist!'],
            ['name'=>'Soo-Jin Kim',     'country'=>'South Korea', 'lang'=>'Korean,English',       'audio'=>3.5, 'video'=>6.5, 'online'=>true,  'rating'=>4.8, 'calls'=>445, 'bio'=>'Busan beauty. Love K-dramas, skincare and deep talks!'],

            // China
            ['name'=>'Mei Lin',         'country'=>'China',       'lang'=>'Chinese,English',      'audio'=>3.0, 'video'=>5.0, 'online'=>true,  'rating'=>4.7, 'calls'=>389, 'bio'=>'Shanghai girl. Modern, sophisticated and very charming!'],
            ['name'=>'Xiao Wei',        'country'=>'China',       'lang'=>'Chinese,English',      'audio'=>2.5, 'video'=>4.5, 'online'=>false, 'rating'=>4.6, 'calls'=>267, 'bio'=>'Beijing beauty. Love Chinese culture, tea and conversations!'],

            // Colombia
            ['name'=>'Valentina Gomez', 'country'=>'Colombia',    'lang'=>'Spanish,English',      'audio'=>2.5, 'video'=>4.5, 'online'=>true,  'rating'=>4.8, 'calls'=>423, 'bio'=>'Colombian beauty from Medellín. Passionate and full of life!'],
            ['name'=>'Sofia Ramirez',   'country'=>'Colombia',    'lang'=>'Spanish',              'audio'=>2.0, 'video'=>4.0, 'online'=>true,  'rating'=>4.7, 'calls'=>312, 'bio'=>'Bogotá girl. Love salsa, coffee and making new friends!'],

            // Ukraine
            ['name'=>'Olga Kovalenko',  'country'=>'Ukraine',     'lang'=>'Ukrainian,English',    'audio'=>3.0, 'video'=>5.0, 'online'=>true,  'rating'=>4.8, 'calls'=>356, 'bio'=>'Ukrainian beauty from Kyiv. Intelligent, warm and caring!'],
            ['name'=>'Darya Bondarenko','country'=>'Ukraine',     'lang'=>'Ukrainian,Russian',    'audio'=>2.5, 'video'=>4.5, 'online'=>false, 'rating'=>4.7, 'calls'=>289, 'bio'=>'Lviv girl. Love art, poetry and meaningful conversations!'],

            // Turkey
            ['name'=>'Ayşe Yilmaz',    'country'=>'Turkey',      'lang'=>'Turkish,English',      'audio'=>2.5, 'video'=>4.5, 'online'=>true,  'rating'=>4.7, 'calls'=>334, 'bio'=>'Istanbul beauty. Love Turkish culture, food and good talks!'],

            // Egypt
            ['name'=>'Nour Hassan',     'country'=>'Egypt',       'lang'=>'Arabic,English',       'audio'=>2.0, 'video'=>4.0, 'online'=>true,  'rating'=>4.6, 'calls'=>245, 'bio'=>'Cairo girl. Warm, friendly and love to share Egyptian culture!'],

            // Nigeria
            ['name'=>'Chioma Okafor',   'country'=>'Nigeria',     'lang'=>'English,Igbo',         'audio'=>2.0, 'video'=>3.5, 'online'=>true,  'rating'=>4.7, 'calls'=>278, 'bio'=>'Lagos beauty. Vibrant, fun and full of African energy!'],

            // South Africa
            ['name'=>'Zara Dlamini',    'country'=>'South Africa','lang'=>'English,Zulu',         'audio'=>2.5, 'video'=>4.0, 'online'=>false, 'rating'=>4.6, 'calls'=>189, 'bio'=>'Cape Town girl. Love nature, music and great conversations!'],

            // Mexico
            ['name'=>'Gabriela Torres', 'country'=>'Mexico',      'lang'=>'Spanish,English',      'audio'=>2.5, 'video'=>4.5, 'online'=>true,  'rating'=>4.8, 'calls'=>367, 'bio'=>'Mexico City beauty. Fiery, passionate and always fun!'],

            // Argentina
            ['name'=>'Camila Fernandez','country'=>'Argentina',   'lang'=>'Spanish,English',      'audio'=>2.5, 'video'=>4.5, 'online'=>true,  'rating'=>4.7, 'calls'=>312, 'bio'=>'Buenos Aires girl. Love tango, football and deep talks!'],

            // Germany
            ['name'=>'Lena Müller',     'country'=>'Germany',     'lang'=>'German,English',       'audio'=>4.0, 'video'=>7.0, 'online'=>false, 'rating'=>4.8, 'calls'=>289, 'bio'=>'Berlin girl. Intellectual, direct and very interesting!'],

            // France
            ['name'=>'Chloé Dubois',    'country'=>'France',      'lang'=>'French,English',       'audio'=>4.5, 'video'=>8.0, 'online'=>true,  'rating'=>4.9, 'calls'=>445, 'bio'=>'Parisian beauty. Romantic, sophisticated and très charmante!'],

            // Italy
            ['name'=>'Giulia Romano',   'country'=>'Italy',       'lang'=>'Italian,English',      'audio'=>4.0, 'video'=>7.0, 'online'=>true,  'rating'=>4.8, 'calls'=>378, 'bio'=>'Rome girl. Passionate about art, food and beautiful conversations!'],

            // Spain
            ['name'=>'Lucia Martinez',  'country'=>'Spain',       'lang'=>'Spanish,English',      'audio'=>3.5, 'video'=>6.0, 'online'=>true,  'rating'=>4.7, 'calls'=>334, 'bio'=>'Barcelona beauty. Love flamenco, beaches and fun chats!'],

            // Pakistan
            ['name'=>'Zara Ahmed',      'country'=>'Pakistan',    'lang'=>'Urdu,English',         'audio'=>1.5, 'video'=>3.0, 'online'=>true,  'rating'=>4.6, 'calls'=>223, 'bio'=>'Lahore girl. Sweet, caring and love to make new friends!'],

            // Bangladesh
            ['name'=>'Nadia Islam',     'country'=>'Bangladesh',  'lang'=>'Bengali,English',      'audio'=>1.5, 'video'=>3.0, 'online'=>false, 'rating'=>4.5, 'calls'=>167, 'bio'=>'Dhaka beauty. Warm, friendly and always cheerful!'],

            // Vietnam
            ['name'=>'Linh Nguyen',     'country'=>'Vietnam',     'lang'=>'Vietnamese,English',   'audio'=>2.0, 'video'=>3.5, 'online'=>true,  'rating'=>4.7, 'calls'=>312, 'bio'=>'Hanoi girl. Sweet, gentle and love Vietnamese culture!'],

            // Indonesia
            ['name'=>'Sari Dewi',       'country'=>'Indonesia',   'lang'=>'Indonesian,English',   'audio'=>2.0, 'video'=>3.5, 'online'=>true,  'rating'=>4.6, 'calls'=>256, 'bio'=>'Jakarta beauty. Friendly, warm and always smiling!'],

            // Malaysia
            ['name'=>'Nurul Ain',       'country'=>'Malaysia',    'lang'=>'Malay,English',        'audio'=>2.0, 'video'=>3.5, 'online'=>true,  'rating'=>4.7, 'calls'=>289, 'bio'=>'KL girl. Love Malaysian food, culture and good conversations!'],

            // Singapore
            ['name'=>'Mei Ling Tan',    'country'=>'Singapore',   'lang'=>'English,Mandarin',     'audio'=>3.5, 'video'=>6.0, 'online'=>true,  'rating'=>4.8, 'calls'=>334, 'bio'=>'Singapore girl. Cosmopolitan, smart and love great conversations!'],
        ];

        // Real Unsplash demo photos (face-cropped, portrait)
        $photos = [
            'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1488716820095-cbe80883c496?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1521119989659-a83eee488004?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1502823403499-6ccfcf4fb453?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1546961342-ea5f62d5a27b?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1519699047748-de8e457a634e?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1489424731084-a5d8b219a5bb?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1479936343636-73cdc5aae0c3?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1463453091185-61582044d556?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1512316609839-ce289d3eba0a?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1526510747491-58f928ec870f?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1541823709867-1b206113eafd?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1530268729831-4b0b9e170218?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1548142813-c348350df52b?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1554151228-14d9def656e4?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1560087637-bf797bc7796a?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1586297135537-94bc9ba060aa?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1587614382346-4ec70e388b28?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1590086782957-93c06ef21604?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1592621385612-4d7129426394?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1595152772835-219674b2a163?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1597586124394-fbd6ef244026?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1601412436009-d964bd02edbc?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1607746882042-944635dfe10e?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1610737241336-371badac3b66?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1614289371518-722f2615943d?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1618641986557-1ecd230959aa?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1621592484082-22b0916d4b8e?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1623582854588-d60de57fa33f?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1627161683077-e34782c24d81?w=400&h=500&fit=crop&crop=face',
            'https://images.unsplash.com/photo-1628157588553-5eeea00af15c?w=400&h=500&fit=crop&crop=face',
        ];

        foreach ($models as $i => $m) {
            $user = User::create([
                'name'           => $m['name'],
                'email'          => 'model' . ($i + 1) . '@livecall.com',
                'phone'          => '90' . str_pad($i + 10, 8, '0', STR_PAD_LEFT),
                'password'       => Hash::make('password'),
                'role'           => 'model',
                'status'         => 'active',
                'phone_verified' => true,
                'country'        => $m['country'],
                'wallet_balance' => rand(500, 5000) * 1.0,
                'avatar'         => null,
            ]);

            ModelProfile::create([
                'user_id'         => $user->id,
                'bio'             => $m['bio'],
                'country'         => $m['country'],
                'languages'       => $m['lang'],
                'audio_price'     => $m['audio'],
                'video_price'     => $m['video'],
                'online_status'   => $m['online'],
                'kyc_status'      => 'approved',
                'total_earnings'  => rand(1000, 50000) * 1.0,
                'total_calls'     => $m['calls'],
                'rating'          => $m['rating'],
                'profile_photo'   => $photos[$i % count($photos)],
            ]);
        }

        // ─── Sample Calls & Transactions for test user ────────────
        $modelUsers = User::where('role', 'model')->take(5)->get();
        foreach ($modelUsers as $idx => $model) {
            $call = Call::create([
                'caller_id'       => $testUser->id,
                'receiver_id'     => $model->id,
                'call_type'       => $idx % 2 === 0 ? 'video' : 'audio',
                'status'          => 'completed',
                'duration'        => rand(120, 600),
                'amount'          => rand(10, 100) * 1.0,
                'price_per_minute'=> $model->modelProfile->audio_price,
                'channel_name'    => 'call_demo_' . $idx,
                'started_at'      => now()->subHours(rand(1, 72)),
                'ended_at'        => now()->subHours(rand(1, 72))->addMinutes(rand(2, 10)),
            ]);

            Transaction::create([
                'user_id'        => $testUser->id,
                'amount'         => $call->amount,
                'type'           => 'call_deduction',
                'status'         => 'completed',
                'description'    => 'Call with ' . $model->name,
                'balance_before' => 1000 + $call->amount,
                'balance_after'  => 1000,
                'call_id'        => $call->id,
            ]);
        }

        // Recharge transaction for test user
        Transaction::create([
            'user_id'        => $testUser->id,
            'amount'         => 1000,
            'type'           => 'recharge',
            'status'         => 'completed',
            'description'    => 'Wallet recharge via Razorpay',
            'balance_before' => 0,
            'balance_after'  => 1000,
            'razorpay_order_id'   => 'order_demo123',
            'razorpay_payment_id' => 'pay_demo123',
        ]);

        // Favorites
        $modelUsers->each(function ($model) use ($testUser) {
            Favorite::create(['user_id' => $testUser->id, 'model_id' => $model->id]);
        });

        // Sample messages
        $firstModel = $modelUsers->first();
        if ($firstModel) {
            Message::create(['sender_id' => $testUser->id, 'receiver_id' => $firstModel->id, 'message' => 'Hi! Can we connect?', 'is_read' => true]);
            Message::create(['sender_id' => $firstModel->id, 'receiver_id' => $testUser->id, 'message' => 'Hello! Sure, I am available now 😊', 'is_read' => false]);
        }

        // ─── Settings ─────────────────────────────────────────────
        Setting::set('commission_rate', 20);
        Setting::set('min_withdrawal', 100);
        Setting::set('site_name', 'LiveCall');
    }
}
