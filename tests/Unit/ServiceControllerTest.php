<?php

namespace Tests\Feature\Admin;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем администратора
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // Создаем обычного пользователя
        $this->user = User::factory()->create([
            'role' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        
        // Создаем тестовые услуги
        $this->service = Service::factory()->create();
    }

    /** @test */
    public function admin_can_view_services_index_page()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(route('admin.services.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('services');
    }

    /** @test */
    public function non_admin_cannot_view_services_index_page()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('admin.services.index'));
        
        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_create_a_service()
    {
        $this->actingAs($this->admin);
        
        Storage::fake('public');
        
        $data = [
            'name' => 'New Service',
            'price' => 100.50,
            'image' => UploadedFile::fake()->image('service.jpg'),
        ];
        
        $response = $this->post(route('admin.services.store'), $data);
        
        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('services', [
            'name' => 'New Service',
            'price' => 100.50,
        ]);
        
        $service = Service::where('name', 'New Service')->first();
        $this->assertNotNull($service->image);
    }

    /** @test */
    public function service_creation_requires_valid_data()
    {
        $this->actingAs($this->admin);
        
        // Попытка создать услугу с неверными данными
        $data = [
            'name' => '', // пустое название
            'price' => 'invalid', // неверный формат цены
            'image' => UploadedFile::fake()->create('document.pdf', 5000), // не изображение
        ];
        
        $response = $this->post(route('admin.services.store'), $data);
        
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'price', 'image']);
    }

    /** @test */
    public function admin_can_edit_a_service()
    {
        $this->actingAs($this->admin);
        
        Storage::fake('public');
        
        $data = [
            'name' => 'Updated Service',
            'price' => 200.75,
            'image' => UploadedFile::fake()->image('new_image.jpg'),
        ];
        
        $response = $this->put(route('admin.services.update', $this->service), $data);
        
        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');
        
        $this->service->refresh();
        $this->assertEquals('Updated Service', $this->service->name);
        $this->assertEquals(200.75, $this->service->price);
        $this->assertNotNull($this->service->image);
    }

    /** @test */
    public function service_update_requires_valid_data()
    {
        $this->actingAs($this->admin);
        
        // Создаем другую услугу для проверки уникальности имени
        $otherService = Service::factory()->create(['name' => 'Existing Service']);
        
        $data = [
            'name' => 'Existing Service', // дублирующееся имя
            'price' => -100, // отрицательная цена
        ];
        
        $response = $this->put(route('admin.services.update', $this->service), $data);
        
        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'price']);
    }

    /** @test */
    public function admin_can_delete_a_service()
    {
        $this->actingAs($this->admin);
        
        Storage::fake('public');
        $imagePath = 'services/test.jpg';
        Storage::disk('public')->put($imagePath, 'dummy content');
        
        $service = Service::factory()->create(['image' => 'storage/' . $imagePath]);
        
        $response = $this->delete(route('admin.services.destroy', $service));
        
        $response->assertRedirect(route('admin.services.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
        Storage::disk('public')->assertMissing('storage/' . $imagePath);
    }

    /** @test */
    public function non_admin_cannot_perform_service_operations()
    {
        $this->actingAs($this->user);
        
        // Попытка создать услугу
        $response = $this->post(route('admin.services.store'), [
            'name' => 'New Service',
            'price' => 100,
        ]);
        $response->assertForbidden();
        
        // Попытка обновить услугу
        $response = $this->put(route('admin.services.update', $this->service), [
            'name' => 'Updated Service',
            'price' => 200,
        ]);
        $response->assertForbidden();
        
        // Попытка удалить услугу
        $response = $this->delete(route('admin.services.destroy', $this->service));
        $response->assertForbidden();
    }

    /** @test */
    public function can_get_service_data_for_editing()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(route('admin.services.edit', $this->service));
        
        $response->assertOk();
        $response->assertJson([
            'name' => $this->service->name,
            'price' => $this->service->price,
        ]);
    }
}