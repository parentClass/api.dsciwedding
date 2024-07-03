<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\RsvpModel;
use App\Models\ErrorModel;

class Rsvp extends BaseController
{

    public function list() {
        $rsvp = new RsvpModel();

        try {
            $response = $rsvp->findAll();
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => "Failed retrieving rsvp list",
                "exception" => $e->getMessage()
            ]);
        }

        return json_encode($response);
    }

    /**
     * save rsvp
     */
    public function record() {
        $rsvp = new RsvpModel();
        $error = new ErrorModel();

        $rsvpData = json_decode(json_encode($this->request->getJSON()), true);

        // validate required fields
        if (!isset($rsvpData['name'])) return json_encode(["message" => "Guest name is missing"]);
        if (!isset($rsvpData['mobile'])) return json_encode(["message" => "Guest mobile is missing"]);
        if (!isset($rsvpData['email'])) return json_encode(["message" => "Guest email is missing"]);

        try {
            if($rsvp->save($rsvpData) == 1) {
                return json_encode([
                    "message" => "Hi " . ucwords($rsvpData['name']) . ", thank you for confirming your attendance! <br/>The couple will be sending an email as well, so stay tuned!"
                ]);
            }
        } catch (\Exception $e) {
            // save to error entries
            $error->save([
                "entry" => "rsvp",
                "message" => "Failed saving the entry, catched by exception",
                "exception" => $e->getMessage()
            ]);

            return $this->response->setStatusCode(400)->setJSON([
                'message' => "Failed saving the entry",
                "exception" => $e->getMessage()
            ]);
        }

        // save to error entries
        $error->save([
            "entry" => "rsvp",
            "message" => "Failed saving the entry, bypassed try catch",
            "exception" => json_encode($this->request->getJSON())
        ]);

        return $this->response->setStatusCode(400)->setJSON([
            'message' => 'Sorry, there seems to be a problem on saving your rsvp entry.<br/>Please try again or on another time'
        ]);
    }
    
    public function approve() {
        $rsvp = new RsvpModel();
        $error = new ErrorModel();

        $rsvpData = json_decode(json_encode($this->request->getJSON()), true);

        try {

            $rsvpEntry = $rsvp->where([
                'id' => $rsvpData['id'], 
                'email' => $rsvpData['email']
            ])->find(1);


            if ($rsvpEntry != null) {
                $email = \Config\Services::email();

                $email->setFrom('rsvp@dsciwedding.com', 'Daniel & Cherrylyn\'s Wedding');
                $email->setTo($rsvpEntry['email']);

                $email->setSubject('Confirmation');

                $template = view("email-sample", ['guest_name' => ucwords($rsvpEntry['name'])]);

                $email->setMessage($template);

                $e = $email->send();

                return json_encode(['mess' => $e]);
            }

        } catch (\Exception $e) {
            // save to error entries
            $error->save([
                "entry" => "rsvp",
                "message" => "Failed approving the rsvp, catched by exception",
                "exception" => $e->getMessage()
            ]);

            return $this->response->setStatusCode(400)->setJSON([
                'message' => "Failed approving the rsvp",
                "exception" => $e->getMessage()
            ]);
        }
    }
}
