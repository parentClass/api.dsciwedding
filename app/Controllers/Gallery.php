<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GalleryModel;

class Gallery extends BaseController
{
    public function list() {
        $galleryModel = new GalleryModel();

        $categorizedGallery = [
          "ceremony" => [],
          "reception" => [],
          "afterparty" => []  
        ];

        $galleries = $galleryModel->orderBy('created_at', 'desc')->findAll();

        foreach($galleries as $gallery) {
            $img = 'https://api.dsciwedding.com/uploads/' . $gallery['category'] . '/' . $gallery['file_name'];

            array_push($categorizedGallery[$gallery['category']], [
                "src" => $img,
                "thumbnail" => generateThumbnail($img, 100, 50, 65)
            ]);
        }

        return json_encode($categorizedGallery);
    }

    public function upload() {
        $galleryModel = new GalleryModel();

        $resp = [
            "ceremony" => [
                "success" => [],
                "failed" => []
            ],
            "reception" => [
                "success" => [],
                "failed" => []
            ],
            "afterparty" => [
                "success" => [],
                "failed" => []
            ]
        ];

        $gallery = [];

        try {
            $categories = ["ceremony", "reception", "afterparty"];

            // Handle file uploads
            $files = $this->request->getFiles();
            
            foreach($categories as $category) {
                foreach ($files[$category] as $file) {
                    $originalName = $file->getName();
                    
                    if($file->getSize() > 0 && $file->getError() == 0) {
                        $uploadDir = FCPATH . 'uploads/' . $category;
    
                        // Check if the directory exists or create it if not
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
        
                        if ($file->isValid() && !$file->hasMoved()) {
                            $fileName = $file->getRandomName();
                            $uploadResult = $file->move($uploadDir, $fileName);
        
                            if($uploadResult == 1) {
                                array_push($gallery, [
                                    "file_name" => $fileName,
                                    "path" => $uploadDir,
                                    "category" => $category
                                ]);
                                array_push($resp[$category]['success'], $originalName);
                            } else {
                                array_push($resp[$category]['failed'], $originalName);
                            }
                        } else {
                            log_message('info', 'failed');
                        }
                    } else {
                        if ($file->getError() == 1) {
                            array_push($resp[$category]['failed'], $originalName . ' => should be less than  10MB');
                        }
                    }
                }
            }

            // should only insert to db if gallery has values
            if($gallery != null) {
                // insert to db
                $galleryModel->insertBatch($gallery);
            }

            return json_encode($resp);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Sorry, there seems to be a problem on uploading your image.',
                'exception' => $e->getMessage()
            ]);
        }
    }

    private function generateThumbnail($img, $width, $height, $quality = 90) {
        if (is_file($img)) {
            $imagick = new Imagick(realpath($img));
            $imagick->setImageFormat('jpeg');
            $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
            $imagick->setImageCompressionQuality($quality);
            $imagick->thumbnailImage($width, $height, false, false);
            $filename_no_ext = reset(explode('.', $img));

            if (file_put_contents($filename_no_ext . '_thumb' . '.jpg', $imagick) === false) {
                throw new Exception("Could not put contents.");
            }

            return true;
        } else {
            throw new Exception("No valid image provided with {$img}.");
        }
    }
}
