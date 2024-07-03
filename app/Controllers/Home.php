<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function download() {
        return $this->response->download('../public/invitation.pdf', null)
            ->setFileName('daniel-and-cherrylyn-wedding-invitation.pdf');
    }
}
