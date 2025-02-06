<?php

use function Pest\Laravel\get;

it('can load listing page', function () {

get(route('home'))->assertStatus(200);
    });
