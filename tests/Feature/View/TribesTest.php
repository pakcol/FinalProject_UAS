<?php

it('can render', function () {
    $contents = $this->view('tribes', [
        //
    ]);

    $contents->assertSee('');
});
