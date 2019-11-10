<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**-------------------Create---------------- */

    /** @test */
    public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'name' => 'Super Product',
                    'price' => '23.30'
                ]
            ]
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                '*' =>  array_keys((new Product())->toArray())
            ]
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);

        $id =  json_encode($response->baseResponse->original->id);
        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }
    
    /** @test */
    public function test_client_cant_create_a_product_atribute_not_send()
    {
        // Given
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'price' => '23.30'
                ]
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
    }

    /** @test */
    public function test_client_cant_create_a_product_price_not_found()
    {
        // Given
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'name' => 'Super Product'
                ]
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
    }

    /** @test */
    public function test_client_cant_create_a_product_price_not_a_number_create()
    {
        // Given
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'name' => 'Super Product',
                    'price' => 'hola buenas tardes, espero estes teniendo un buen dia'
                ]
            ]
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
    }
    
    /** @test */
    public function test_client_cant_create_a_product_price_less_than_or_equal_to_zero()
    {
        // Given
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'name' => 'Super Product',
                    'price' => '-15'
                ]
            ]
        ];
        // When
        $response = $this->json('POST', '/api/products', $productData); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
    }
    /** ----------------------Create----------------------- */

    /** ----------------------SHOW-------------------------*/
    /** @test */
    public function test_client_can_show_a_product()
    {
        //factories
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;

        //response
        $response = $this->json('GET', '/api/products/'. $id .'');
        $response->assertStatus(200);
        $this->assertJsonStringEqualsJsonString(
            json_encode($producto),
            json_encode($response->baseResponse->original)
        );
    }

    /** @test */
    public function test_client_cant_show_a_product_id_dont_exist()
    {
        //factory
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        
        //response
        $response = $this->json('GET', '/api/products/-100');
        $response->assertStatus(404);
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
    }
    /** -------------------- Show ------------------------ */

    /** --------------------Delete------------------------ */
    /** @test */
    public function test_client_can_delete_products()
    {
        //Given
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $response = $this->call('DELETE', '/api/products/'.$id);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseMissing('products', [
            'id' => $id,
            'name' => $nombre,
            'price' => $precio
        ]);
    }

    /** @test */
    public function test_client_cant_delete_products_ID_does_not_exist()
    {
        //factory
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;

        //response
        $response = $this->call('DELETE', '/api/products/-100');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertDatabaseHas('products', [
            'id' => $id,
            'name' => $nombre,
            'price' => $precio
        ]);
    }
    /** ----------------------Delete--------------------- */

    /** ----------------------Update--------------------- */
    /** @test */
    public function test_client_can_update_a_product()
    {
        //factory
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'name' => 'Super Product',
                    'price' => '23.30'
                ]
            ]
        ];

        //response
        $response = $this->json('PUT', '/api/products/'. $id .'', $productData);
        $this->assertEquals(200, $response->getStatusCode());
        $valor = $this->json('GET', '/api/products/'. $id .'');
        $this->assertJsonStringNotEqualsJsonString(
            json_encode($producto),
            json_encode($valor->baseResponse->original)
        );
        
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }

    /** @test */
    public function test_client_cant_update_a_product_price_is_not_a_number()
    {
        //Factory
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'data' => [
                'type' => "products",
                'attributes' => [
                    'name' => 'Super Product',
                    'price' => 'Hola, sigue bajando, aqui no hay nada que ver'
                ]
            ]
        ];

        //response
        $response = $this->json('PUT', '/api/products/'. $id .'', $productData);
        $this->assertEquals(422, $response->getStatusCode());
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
        $valor = $this->json('GET', '/api/products/'. $id .'');
        $this->assertJsonStringEqualsJsonString(
            json_encode($producto),
            json_encode($valor->baseResponse->original)
        );
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => $nombre,
                'price' => $precio
            ]
        );
    }
    
    /** @test */
    public function test_client_cant_update_a_product_price_attribute_is_less_than_or_equal_to_zero()
    {
        //factory
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'name' => 'Producto 2',
            'price' => '-10'
        ];

        //response
        $response = $this->json('PUT', '/api/products/'. $id .'', $productData);
        $this->assertEquals(422, $response->getStatusCode());
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
        $valor = $this->json('GET', '/api/products/'. $id .'');
        $this->assertJsonStringEqualsJsonString(
            json_encode($producto),
            json_encode($valor->baseResponse->original)
        );
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $id,
                'name' => $nombre,
                'price' => $precio
            ]
        );
    }
    
    /** @test */
    public function test_client_cant_update_productthe_ID_does_not_exist()
    {
        //factory
        $producto = factory(Product::class)->create();
        $nombre = $producto->name;
        $precio = $producto->price;
        $id = $producto->id;
        $productData = [
            'name' => 'Producto 2',
            'price' => '-10'
        ];

        //response
        $response = $this->json('PUT', '/api/products/-10', $productData);
        $this->assertEquals(422, $response->getStatusCode());
        $response->assertJsonStructure([
            "errors" => [
                "code",
                "title",
                "type"]
        ]);
    }
    /** -------------------------- Update ----------------------- */

    /** -------------------------- LIST ------------------------- */
    /** @test */
    public function test_client_can_show_all_products()
    {
        //factory 1
        $producto1 = factory(Product::class)->create();
        $nombre = $producto1->name;
        $precio = $producto1->price;
        $id = $producto1->id;
        
        //factory 2
        $producto2 = factory(Product::class)->create();
        $nombre = $producto2->name;
        $precio = $producto2->price;
        $id = $producto2->id;

        //response
        $response = $this->json('GET', '/api/products/');
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /** @test */
    public function test_client_can_show_all_products_when_products_is_empty(){
        $productData = '{"baseResponse":{"headers":{},"original":[],"exception":null}}';
        $response = $this->json('GET', '/api/products/');
        $valor = json_encode($response);
        $this->assertEquals($productData, $valor);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
//Created By Super Product