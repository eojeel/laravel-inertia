<?php

declare(strict_types=1);

it('homepage returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});
