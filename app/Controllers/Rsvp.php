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
            $response = $rsvp->orderBy('is_approved', 'asc')->findAll();
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => "Failed retrieving rsvp list",
                "exception" => $e->getMessage()
            ]);
        }

        return json_encode($response);
    }

    public function stats() {
        $rsvp = new RsvpModel();

        try {
            $response = $rsvp->findAll();
            $formCount = count($response);
            $pasilunganAttendeesCount = 0;
            $approvedAttendeesCount = 0;
            $declinedAttendeesCount = 0;

            foreach ($response as $key => $value) {
                if($response[$key]["is_pasilungan_attending"] == 1) {
                    $pasilunganAttendeesCount += 1;
                }

                if($response[$key]["is_approved"] == 1) {
                    $approvedAttendeesCount += 1;
                }

                if($response[$key]["is_declined"] == 1) {
                    $declinedAttendeesCount += 1;
                }
            }

        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => "Failed retrieving rsvp stats",
                "exception" => $e->getMessage()
            ]);
        }

        return json_encode([
            "form_sent" => $formCount,
            "pasilungan_attendees" => $pasilunganAttendeesCount,
            "approved_attendees" => $approvedAttendeesCount,
            "declined_attendees" => $declinedAttendeesCount
        ]);
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
            'message' => 'Sorry, there seems to be a problem on saving your rsvp entry.<br/>Please try again using a different email or maybe on another time. thank you!'
        ]);
    }
    
    public function approve() {
        $rsvp = new RsvpModel();
        $error = new ErrorModel();

        $rsvpData = json_decode(json_encode($this->request->getJSON()), true);

        try {

            $rsvpEntry = $rsvp->where([
                'id' => $rsvpData['id'], 
                'email' => $rsvpData['email'],
                'is_approved' => false
            ])->first();

            if ($rsvpEntry != null) {
                $email = \Config\Services::email();

                $email->setFrom('rsvp@dsciwedding.com', 'Daniel & Cherrylyn\'s Wedding');
                $email->setTo($rsvpEntry['email']);

                $email->setSubject('Confirmation');

                $template = view("rsvp-confirm-template", ['guest_name' => ucwords($rsvpEntry['name'])]);

                $email->setMessage($template);

                if ($email->send()) {
                    // change status of is approved
                    $rsvp->where([
                        'id' => $rsvpData['id'], 
                        'email' => $rsvpData['email'],
                        'is_approved' => false
                    ])->set(['is_approved' => true, 'updated_at' => date('Y-m-d H:i:s')])->update();

                    return json_encode([
                        'message' => 'Confirmation email has been sent!'
                    ]);
                } else {
                    return $this->response->setStatusCode(400)->setJSON([
                        'message' => "Failed sending confirmation email for approval",
                        "exception" => $e->getMessage()
                    ]);
                }
            } else {
                return $this->response->setStatusCode(400)->setJSON([
                    'message' => "Rsvp is either approved already or not existing"
                ]);
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

    public function decline() {
        $rsvp = new RsvpModel();
        $error = new ErrorModel();

        $rsvpData = json_decode(json_encode($this->request->getJSON()), true);

        try {

            $rsvpEntry = $rsvp->where([
                'id' => $rsvpData['id'], 
                'email' => $rsvpData['email'],
                'is_approved' => 0,
                'is_declined' => 0
            ])->first();

            // change status of is approved
            $rsvp->where([
                'id' => $rsvpData['id'], 
                'email' => $rsvpData['email'],
                'is_approved' => false,
                'is_declined' => false
            ])->set(['is_declined' => true, 'updated_at' => date('Y-m-d H:i:s')])->update();

            return json_encode([
                'message' => 'Rsvp has been successfuly declined!'
            ]);

        } catch (\Exception $e) {
            // save to error entries
            $error->save([
                "entry" => "rsvp",
                "message" => "Failed declining the rsvp, catched by exception",
                "exception" => $e->getMessage()
            ]);

            return $this->response->setStatusCode(400)->setJSON([
                'message' => "Failed declining the rsvp",
                "exception" => $e->getMessage()
            ]);
        }
    }
}
