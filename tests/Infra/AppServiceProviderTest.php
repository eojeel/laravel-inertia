<?php

use Illuminate\Support\Facades\URL;

it('forces HTTPS scheme when enabled', function (): void {

        $generated = URL::to(route('home'));
        expect($generated)->toStartWith('https://');
});
