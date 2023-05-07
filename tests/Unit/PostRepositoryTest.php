<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post()
    {

        $user = User::factory()->create();

        $this->actingAs($user);
    
        $post = Post::factory()->make()->toArray();
        $category = Category::factory()->count(2)->create();
        $category_id = $category->map(fn($item) => $item->id)->toArray();
        $post['category_id'] = $category_id;

        $post_repo = app(PostRepository::class);

        $data = $post_repo->createPost($post);

        $this->assertEquals($post['title'] , $data->title);
        $this->assertCount(2, $data->category);
        $this->assertNotNull($data->slug);
        $this->assertEquals('draft' , $data->status);
    }


    public function test_update_post()
    {
        $this->test_create_post();

        $post_repo = app(PostRepository::class);
        
        $user = User::factory()->create();
        $this->actingAs($user);
    

        $post = Post::first();

        $category = Category::factory()->count(4)->create();
        $category_id = $category->map(fn($item) => $item->id)->toArray();

        $param['title'] = 'Ini Artikel';
        $param['category_id'] = $category_id;

        $data = $post_repo->updatePost($post , $param);

        $this->assertEquals($param['title'] , $data->title);
        $this->assertCount(4, $data->category);
        $this->assertEquals('draft', $data->status);
    }
}
