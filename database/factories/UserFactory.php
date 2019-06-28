<?php
use Faker\Generator as Faker;

$factory->define(App\User_type::class, function (Faker $faker) {

    return[
      'name' => $faker->name,
      'description' => $faker->lastName,
    ];
  });

  $factory->define(App\Product_category::class, function (Faker $faker) {

    return[
      'name' => $faker->word,
      'description' => $faker->lastName,
    ];
  });

  $factory->define(App\Company::class, function (Faker $faker) {

      return[
        'name' => $faker->name,
        'description' => $faker->lastName,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'rfc' => $faker->creditCardNumber,
        'email_billing' => $faker->unique()->safeEmail,
        'page_web' => $faker->domainName,
      ];
  });

    $factory->define(App\Sector::class, function (Faker $faker) {

        return[
          'name' => $faker->word,
          'description' => $faker->lastName,
        ];
    });

      $factory->define(App\Balance::class, function (Faker $faker) {

          return[
            'id_customer' => App\Customer::all()->random()->id,
            'total'=> rand(0, 1000),
          ];
      });

      $factory->define(App\Order::class, function (Faker $faker) {
        $number = 0;
          return[
            'folio' => $number+1,
            'total' => rand(20, 1000),
            'paid' => rand(0, 1),
            'id_user' => App\User::all()->random()->id,
            'id_customer' => App\Customer::all()->random()->id,
          ];
        });
//
        $factory->define(App\Payment::class, function (Faker $faker) {

            return[
              'quantity' => rand(150, 350),
              'id_order' => App\Order::all()->random()->id,
            ];
          });

        $factory->define(App\Delivery::class, function (Faker $faker) {

            return[
              'id_order' => App\Order::all()->random()->id,
              'description' => $faker->address,
            ];
        });
        $factory->define(App\Product::class, function (Faker $faker) {
            return [
                'name' => $faker->word,
                'description'=> $faker->address,
                'img_url' => App\Pivote::all()->random()->url,
                'price' => rand(0, 70),
                'code' => str_random(30),
                'status' => $faker->word,
                'id_category' => App\Product_category::all()->random()->id,
                'id_company' => App\Company::all()->random()->id,
            ];
        });

        $factory->define(App\Sale::class, function (Faker $faker) {

            return[
              'quantity' => rand(20, 2500),
              'id_order' => App\Order::all()->random()->id,
              'id_product' => App\Product::all()->random()->id,
            ];
          });
$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'last_name'=> $faker->lastName,
        'img_url' => App\Pivote::all()->random()->url,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,//este lo tebias mal escrito estaba solo phone
        'address'=> $faker->address,
        'remember_token' => str_random(10),
        'password' => $password ?: $password = bcrypt('secret'),
        // 'id_usertype' => App\User_type::all()->random()->id, //aqui estabas generando una cadena aleatoria y la querias insertar en un string, aqui es donde tienes que hacer la relacion con la tabla user types para que genere id aleatorios de esa tabla en especifico
        'id_company' => App\Company::all()->random()->id,
    ];
});

$factory->define(App\Customer::class, function (Faker $faker) {

    return[
      'code' => str_random(5),
      'name' => $faker->name,
      'last_name' => $faker->lastName,
      'phone' => $faker->phoneNumber,
      'email' => $faker->unique()->safeEmail,
      'street' => $faker->word,
      'colony' => $faker->name,
      'city' => $faker->city,
      'state' => $faker->lastName,
      'cp' => rand(44700, 44800),
      'external_number' => str_random(2),
      'internal_number' => str_random(2),
      'latitude' => rand(17, 31),
      'longitude' => rand(-97, -105),
      'id_warehouse' => App\Warehouse::all()->random()->id,
      'id_company' => App\Company::all()->random()->id,
      'id_sector' => App\Sector::all()->random()->id,
    ];
  });

$factory->define(App\Warehouse::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description'=> $faker->address,
        'type_warehouse' => rand(0, 1),
        'id_user' => App\User::all()->random()->id,
    ];
});

$factory->define(App\Stocktaking::class, function (Faker $faker) {
    return[
      'id_warehouse' => App\Warehouse::all()->random()->id,
      'id_product' => App\Product::all()->random()->id,
      'quantity' => rand(20, 100),
    ];
  });
