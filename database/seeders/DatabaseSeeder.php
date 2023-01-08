<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Category, Comment, Post, User, Label, Like};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->users()->categories()->posts()->likes()->comments();

        $this->persianData()->likes()->comments();
    }

    private function users()
    {
        User::factory()->create([
            'first_name' => 'Amir',
            'last_name' => 'Abroun',
            'email' => 'abroon234@gmail.com',
            'password' => 12345678,
            'is_admin' => 1,
        ]); // admin user Amir Abroun

        User::factory()->create([
            'first_name' => 'جواد',
            'last_name' => 'حسین آبادی',
            'email' => 'javad123@gmail.com',
            'password' => 12345678,
            'is_admin' => 1,
        ]);

        User::factory()->create([
            'first_name' => 'مهیار',
            'last_name' => 'حسین آبادی',
            'email' => 'mahyar123@gmail.com',
            'password' => 12345678,
            'is_admin' => 1,
        ]);

        User::factory(rand(4, 10))->create();

        return $this;
    }

    private function categories()
    {
        Category::factory(rand(4, 10))->create();

        Category::factory()->create([
            'title' => 'Exam',
            'description' => 'Exam category. You can see exam dates in this category'
        ]);

        Category::factory()->create([
            'title' => 'Practis',
            'description' => 'Practis category. You can see practis dates and logs in this category'
        ]);

        Category::factory(1)->create([
            'title' => 'Travel',
            'description' => 'Traveling is such a nice experience. 
                Besides earning knowledge about other people and history of other nations; 
                the essense of travel that makes unpredictble days, makes us efficient and capable.'
        ]);

        Category::factory(1)->create([
            'title' => 'SEO commercial',
            'description' => 'A company for improving SEO science.'
        ]);

        return $this;
    }

    private function posts()
    {
        Post::factory(rand(4, 10))
            ->hasAttached(
                Label::factory(6)->create()
            )
            ->sequence(
                fn () => [
                    'user_id' => User::all()->random(),
                    'category_id' => Category::all()->random(),
                ]
            )->create();

        Post::factory(rand(3, 10))->hasAttached(
            Label::factory(rand(3, 9))->create()
        )->sequence(
            fn () => [
                'user_id' => User::all()->random(),
                'category_id' => Category::all()->random(),
            ]
        )->create();

        return $this;
    }

    public function likes()
    {
        Like::factory(User::count())->sequence(
            fn () => [
                'likeable_id' => Post::all()->random(),
            ]
        )->create();

        return $this;
    }

    public function comments()
    {
        Comment::factory(rand(100, 200))->sequence(
            fn () => [
                'commentable_id' => Post::all()->random(),
            ]
        )->create();

        return $this;
    }

    public function persianData()
    {
        Category::create([
            'title' => 'هنری',
            'description' => 'مطالب هنری'
        ]);

        Category::create([
            'title' => 'ورزشی',
            'description' => 'مطالب ورزشی'
        ]);

        Post::create([
            'user_id' => 2,
            'category_id' => Category::query()->where('title', 'هنری')->first()->id,
            'title' => 'گرانترین تابلو نقاشی جهان',
            'body' => 'این نقاشی از چهره حضرت مسیح در حالی که گویی شیشه‌ای به دست دارد، گران‌ترین تابلو نقاشی جهان به شمار می‌رود. داوینچی این اثر ارزشمند را به سفارش لویی دوازدهم، پادشاه آن زمان فرانسه نقاشی کرده است و جالب است بدانید داوینچی در همان زمانی روی این اثر کار می‌کرده است که مشغول نقاشی مونالیزا هم بوده است. نکته دیگر درباره این اثر ناپدید شدن چند صد ساله آن است تا اینکه گروهی از دلالان هنری بریتانیایی در سال ۲۰۰۵ این تابلو چشم‌نواز را دوباره کشف کردند.',
            'image_url' => 'yUMTsXxhGuuBRQjQqR6Ongqo0anFRfWxhlWuwdbv.jpg'
        ]);

        Post::create([
            'user_id' => 3,
            'category_id' => Category::query()->where('title', 'ورزشی')->first()->id,
            'title' => 'رونالدو به النصر پیوست',
            'body' => 'این ستاره پرتغالی به عنوان فوتبالیست تا ۳۰ ژوئن ۲۰۲۵ با باشگاه النصر قرارداد دارد. میزان دستمزد او در این قرارداد تا ۲۲۵ میلیون یورو خواهد شد اما در کنار آن کارهایی قرار است انجام شود که موجب ایجاد تاخیر در رسیدن به توافق پس از پایان جام جهانی شد.',
            'image_url' => 'FHIYYJ5v9plco5AshrkGmyo4RXqTDSAnszZB6pZH.jpg'
        ]);

        Post::create([
            'user_id' => 3,
            'category_id' => Category::query()->where('title', 'هنری')->first()->id,
            'title' => 'خرس های قطبی را نمی توان با دوربین های مادون قرمز شناسایی کرد',
            'body' => 'این حیوانات به دلیل لایه ی ضخیم چربی زیر پوست شان و موهای فراوانی که بدن شان را پوشانده، بدن کاملا گرمی دارند. اما لایه ی بیرونی بدن آن ها همان دمای برف های محیط اطراف شان را دارد. بنابراین با دوربین های مادون قرمز قابل تشخیص نیستند چون این دوربین ها به وسیله ی تشخیص گرمای آزاد شده از بدن موجودات زنده کار می کنند.',
            'image_url' => '8tjalABUDv38YQ1fidMe5Kkrc3xTKXTbyML0hZLk.jpg'
        ]);

        return $this;
    }
}
