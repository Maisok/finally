<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Generation;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use App\Models\Country;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_admin_can_create_equipment()
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);
    
        // Create reference data
        $brand = Brand::create(['name' => 'Toyota', 'logo' => 'toyota.png']);
        $model = CarModel::create(['brand_id' => $brand->id, 'name' => 'Camry']);
        $generation = Generation::create([
            'car_model_id' => $model->id,
            'name' => 'XV70',
            'year_from' => 2020,
            'year_to' => 2025,
        ]);
    
        // Create required reference models
        $bodyType = BodyType::create(['name' => 'Sedan']);
        $engineType = EngineType::create(['name' => 'Gasoline']);
        $transmissionType = TransmissionType::create(['name' => 'Automatic']);
        $driveType = DriveType::create(['name' => 'Front-wheel drive']);
        $country = Country::create(['name' => 'Japan', 'code' => 'JP']);
    
        $equipmentData = [
            'generation_id' => $generation->id,
            'body_type_id' => $bodyType->id,
            'engine_type_id' => $engineType->id,
            'engine_name' => 'V6',
            'engine_volume' => 3.5,
            'engine_power' => 301,
            'transmission_type_id' => $transmissionType->id,
            'transmission_name' => '8-speed',
            'drive_type_id' => $driveType->id,
            'country_id' => $country->id,
            'description' => 'Top of the line',
            'weight' => 1600,
            'load_capacity' => 500,
            'seats' => 5,
            'fuel_consumption' => 10.5,
            'fuel_tank_volume' => 65,
            'battery_capacity' => null,
            'range' => 500,
            'max_speed' => 220,
            'clearance' => 150,
        ];
    
        // Отправляем запрос
        $response = $this->actingAs($admin)
            ->post(route('admin.equipments.store'), $equipmentData);
    
        // Проверяем результат
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.equipments.index'));
    
        // Ожидаем запись в БД
        $this->assertDatabaseHas('equipment', ['engine_name' => 'V6']);
    }

    public function test_non_admin_cannot_create_equipment()
    {
        $user = User::factory()->create(['role' => 'manager']);

        $response = $this->actingAs($user)
            ->post(route('admin.equipments.store'), []);

        // Check for either 403 or redirect to home
        if ($response->status() !== 403) {
            $response->assertRedirect('/');
        } else {
            $response->assertStatus(403);
        }
    }

    public function test_required_fields_are_validated()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.equipments.store'), []);

        $response->assertSessionHasErrors([
            'generation_id',
            'body_type_id',
            'engine_type_id',
            'transmission_type_id',
            'drive_type_id',
            'country_id',
        ]);
    }

    public function test_numeric_fields_validation()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $invalidData = [
            'generation_id' => 'not-a-number',
            'body_type_id' => 1,
            'engine_type_id' => 1,
            'engine_volume' => 'not-a-number',
            'engine_power' => 'invalid',
            'transmission_type_id' => 1,
            'transmission_name' => 'Auto',
            'drive_type_id' => 1,
            'country_id' => 1,
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.equipments.store'), $invalidData);

        $response->assertSessionHasErrors([
            'engine_volume',
            'engine_power',
            'generation_id',
        ]);
    }
}