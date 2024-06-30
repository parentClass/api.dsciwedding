<?php

namespace App\Controllers;

use App\Models\FaqModel;

class Faq extends BaseController
{
    /**
     * retrieve all faq list
     */
    public function list()
    {
        try {
            $faqModel = new FaqModel();
            $response['faqs'] = $faqModel->findAll();
        } catch (\Exception $e) {
            $response['message'] = "Failed on retrieving faq list";
            $response['exception'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function faqLookupById($id) {
        try {
            $faqModel = new FaqModel();
            // find first faq
            $response = $faqModel->where('id', $id)->find(1);
        } catch (\Exception $e) {
            $response['message'] = "Failed on retrieving the specified faq";
            $response['exception'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function faqLookupByCategory($category)
    {
        try {
            $faqModel = new FaqModel();
            // find all faq by category
            $response = $faqModel->where('category', $category)
                ->orderBy('ordinal', 'asc')->findAll();
        } catch (\Exception $e) {
            $response['message'] = "Failed on retrieving the specified faq";
            $response['exception'] = $e->getMessage();
        }

        return json_encode($response);
    }
}
