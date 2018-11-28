<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionApiTest extends TestCase
{
    use MakeSubscriptionTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSubscription()
    {
        $subscription = $this->fakeSubscriptionData();
        $this->json('POST', '/api/v1/subscriptions', $subscription);

        $this->assertApiResponse($subscription);
    }

    /**
     * @test
     */
    public function testReadSubscription()
    {
        $subscription = $this->makeSubscription();
        $this->json('GET', '/api/v1/subscriptions/'.$subscription->id);

        $this->assertApiResponse($subscription->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSubscription()
    {
        $subscription = $this->makeSubscription();
        $editedSubscription = $this->fakeSubscriptionData();

        $this->json('PUT', '/api/v1/subscriptions/'.$subscription->id, $editedSubscription);

        $this->assertApiResponse($editedSubscription);
    }

    /**
     * @test
     */
    public function testDeleteSubscription()
    {
        $subscription = $this->makeSubscription();
        $this->json('DELETE', '/api/v1/subscriptions/'.$subscription->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/subscriptions/'.$subscription->id);

        $this->assertResponseStatus(404);
    }
}
