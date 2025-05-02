<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition()
    {
        $cities = ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Adana', 'Antalya', 'Trabzon', 'Konya', 'Gaziantep', 'Diyarbakır'];
        $genders = ['E', 'K'];
        $maritalStatuses = ['Bekar', 'Evli', 'Dul', 'Boşanmış'];
        $professions = ['Mühendis', 'Doktor', 'Öğretmen', 'Avukat', 'Mimar', 'Hemşire', 'Esnaf', 'Öğrenci', 'Serbest Meslek'];

        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'age' => $this->faker->numberBetween(18, 90),
            'tc' => $this->generateTCKN(),
            'address' => $this->faker->address(),
            'phone' => $this->generatePhoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'birth_date' => $this->faker->dateTimeBetween('-90 years', '-18 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement($genders),
            'marital_status' => $this->faker->randomElement($maritalStatuses),
            'profession' => $this->faker->randomElement($professions),
            'city' => $this->faker->randomElement($cities),
            'country' => 'Türkiye',
            'postal_code' => $this->faker->postcode(),
            'notes' => $this->faker->sentence(6),
        ];
    }

    private function generateTCKN()
    {
        return $this->faker->unique()->numerify('###########');
    }

    private function generatePhoneNumber()
    {
        return $this->faker->numerify('+905#########');
    }
}