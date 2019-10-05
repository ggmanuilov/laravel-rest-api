<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortLinkTest extends TestCase
{
    use RefreshDatabase;

    public function testLinkList()
    {
        $response = $this->getJson(route('links'), []);

        $response
            ->assertStatus(200)
            ->assertJson([]);
    }

    public function testLinkAdd()
    {
        $response = $this->postJson(route('links.store'), [
            'long_url' => 'http://test.test'
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'id'        => 1,
                'short_url' => 'b'
            ]);
    }

    public function testInvalidLinkAdd()
    {
        $postData = ['long_url' => 'test.test'];
        $this->json('POST', route('links.store'), $postData)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors'  => [
                    "long_url" => [
                        "The long url format is invalid."
                    ]
                ]
            ]);
    }

    public function testResolveLinkIfShortLinkExists()
    {
        $linkResponse = $this->postJson(route('links.store'), [
            'long_url' => 'http://test.test'
        ]);
        /**@var $linkResponse TestResponse */
        $linkResponse = json_decode($linkResponse->baseResponse->getContent());

        $response = $this->get(route('links.resolve', $linkResponse->short_url));

        $response
            ->assertStatus(302)
            ->assertRedirect('http://test.test');

        // check hits
        $response = $this->getJson(route('links.show', $linkResponse->id));
        $response
            ->assertStatus(200)
            ->assertJson([
                "id"       => 2,
                "long_url" => "http://test.test",
                "hits"     => 1,
                "short"    => "http://localhost/resolve/c"
            ]);
    }

    public function testResolveLinkIfShortLinkNotExists()
    {
        $response = $this->get(route('links.resolve', 'sdwqr1'));

        $response->assertStatus(404);
    }

    public function testDeleteLink()
    {
        $linkResponse = $this->postJson(route('links.store'), [
            'long_url' => 'http://test.test'
        ]);

        /**@var $linkResponse TestResponse */
        $linkResponse = json_decode($linkResponse->baseResponse->getContent());

        $response = $this->deleteJson(route('links.delete', ['id' => $linkResponse->id]));

        $response->assertStatus(204);
    }

    public function testLinkAddInvalidUrl()
    {
        $response = $this->postJson(route('links.store'), [
            'long_url' => ''
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "The given data was invalid.",
                "errors"  => ["long_url" => ["The long url field is required."]]
            ]);
    }

    public function testChangeLink()
    {
        // add
        $response = $this->postJson(route('links.store'), [
            'long_url' => 'http://test.test'
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'id'        => 4,
                'short_url' => 'f'
            ]);

        $assertNewEntity = [
            "id"       => $response->baseResponse->original['id'],
            "long_url" => "http://test.test",
            "hits"     => 0,
            "short"    => "http://localhost/resolve/b"
        ];

        $params = array_merge($assertNewEntity, [
            'long_url' => 'http://super.nova'
        ]);

        $response = $this->putJson(route('links.update', ['id' => $response->baseResponse->original['id']]), $params);
        $response->assertStatus(200)
            ->assertJson([
                "id"       => 4,
                "long_url" => "http://super.nova",
                "hits"     => 0,
                "short"    => "http://localhost/resolve/f"
            ]);
    }
}
