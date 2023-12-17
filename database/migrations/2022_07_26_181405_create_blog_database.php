<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('address')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();

            $table->softDeletes();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title')->unique();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('label_post', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained();
            $table->foreignId('label_id')->constrained();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->morphs('likeable');
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->morphs('commentable');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('summary')->nullable();
            $table->string('position')->nullable();
            $table->json('experiences')->nullable();
            $table->json('education')->nullable();
            $table->json('skills')->nullable();
            $table->json('contact')->nullable();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->morphs('model');
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('followables', function (Blueprint $table) {
            $table->id();
            $table->morphs('followable');
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index(['followable_type', 'accepted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');

        Schema::dropIfExists('comments');

        Schema::dropIfExists('posts');

        Schema::dropIfExists('labels');

        Schema::dropIfExists('label_post');

        Schema::dropIfExists('users');

        Schema::dropIfExists('categories');

        Schema::dropIfExists('resumes');

        Schema::dropIfExists('media');

        Schema::dropIfExists('personal_access_tokens');

        Schema::dropIfExists('followables');
    }
};
