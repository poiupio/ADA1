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
    
    public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }

    public function test_client_create_a_nameless_product()
    {
        // Given
        $productData = [
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-1',
            'title' => 'Unprocessable Entity'
        ]);
        
    }

    public function test_client_create_a_priceless_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-1',
            'title' => 'Unprocessable Entity'
        ]);
    }

    public function test_client_create_a_negative_price_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '-10'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-1',
            'title' => 'Unprocessable Entity'
        ]);
    }

    

    public function test_client_show_all()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );

        $response = $this->json('GET', '/api/products'); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        
        $body = $response->decodeResponseJson();
    }

    public function test_client_show_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );

        $response = $this->json('GET', '/api/products/3'); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);

        $body = $response->decodeResponseJson();
    }

    public function test_client_show_inexistent_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );

        $response = $this->json('GET', '/api/products/30'); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);

         // Assert the response has the correct structure
         $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-2',
            'title' => 'Not Found'
        ]);

        $body = $response->decodeResponseJson();
    }

    public function test_client_edit_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );


        $productData2 = [
            'name' => 'cool product',
            'price' => '10'
        ];

        $response = $this->json('put', '/api/products/4',$productData2); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'cool product',
            'price' => '10'
        ]);

        $body = $response->decodeResponseJson();
    }

    public function test_client_edit_inexistent_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );


        $productData2 = [
            'name' => 'cool product',
            'price' => '10'
        ];

        $response = $this->json('put', '/api/products/40',$productData2); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-1',
            'title' => 'Unprocessable Entity'
        ]);
    }


    public function test_client_edit_negative_price_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );


        $productData2 = [
            'name' => 'cool product',
            'price' => '-10'
        ];

        $response = $this->json('put', '/api/products/4',$productData2); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-1',
            'title' => 'Unprocessable Entity'
        ]);
    }


    public function test_client_edit_no_number_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );


        $productData2 = [
            'name' => 'cool product',
            'price' => 'b'
        ];

        $response = $this->json('put', '/api/products/4',$productData2); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-1',
            'title' => 'Unprocessable Entity'
        ]);
    }

    public function test_client_destroy()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );

        $response = $this->json('delete', '/api/products/5'); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
        
        $body = $response->decodeResponseJson();
    }

    public function test_client_destroy_inexistent_product()
    {
        $response = $this->json('delete', '/api/products/5'); 
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);

         // Assert the response has the correct structure
         $response->assertJsonStructure([
            'code',
            'title'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'ERROR-2',
            'title' => 'Not Found'
        ]);
    }
}
