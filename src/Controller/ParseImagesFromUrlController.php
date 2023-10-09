<?php

namespace App\Controller;

use DOMDocument;
use DOMXPath;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use voku\helper\HtmlDomParser;

class ParseImagesFromUrlController extends AbstractController
{
    /**
     * @param string $url
     *
     * @return Response
     * @throws Exception
     */
    #[Route('/api/v1/parse-images/{url}', name: 'app_parse_images')]
    public function index(string $url): Response
    {

        $parsedUrl = parse_url(str_replace('*', '/', $url));
        $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        if (isset($parsedUrl['path'])) {
            $url .= $parsedUrl['path'];
        }
        $domain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $html = HtmlDomParser::file_get_html($url);
        $elements = $html->getElementsByTagName('img');
        $images = [];

        foreach ($elements as $element) {
            $doc = new DOMDocument();
            $doc->loadHTML($element);
            $xpath = new DOMXPath($doc);
            $src = $xpath->evaluate("string(//img/@src)"); # "/images/image.jpg"
            if (filter_var($src, FILTER_VALIDATE_URL)) {
                try {
                    $headers = get_headers($src, 1);
                    if (isset($headers['Content-Length'])) {
                        $images[] = ['path' => $src, 'weight' => $headers['Content-Length'] / 1024];
                    }
                } catch (\Exception $exception) {
                    throw new Exception('Couldn\'t load the size of image');
                }
            } else {
                if ($src[0] !== '/') {
                    $src = '/' . $src;
                }
                try {
                    $headers = get_headers($domain . $src, 1);
                    if (isset($headers['Content-Length'])) {
                        $images[] = ['path' => $domain . $src, 'weight' => $headers['Content-Length'] / 1024];
                    }
                } catch (\Exception $exception) {
                    throw new Exception('Couldn\'t load the size of image');
                }

            }

        }

        return $this->json([
            'images' => $images,
            'totalWeight' => array_sum(array_column($images, 'weight')),
        ]);
    }
}
