<?php

namespace TBMintbase\Model;

use TBMintbase\Model\Interfaces\IThing;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ThingModel implements IThing
{
    public $id;

    public $metaId;

    public $memo;

    public $baseUri;

    public $nft;

    public function __construct($id, $metaId, $memo, $baseURi)
    {
        $this->id = $id;
        $this->metaId = $metaId;
        $this->memo = $memo;
        $this->baseUri = $baseURi;
        $this->getNft();
    }

    public function getNft()
    {
        try {
            $result = $this->getClient()->request(
                'GET',
                "$this->baseUri/$this->metaId",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                ]
            );
            $data = (array)json_decode((string)$result->getBody());
            if ($data) {
                $this->nft = $data;
            }
        } catch (RequestException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function getClient()
    {
        return new Client([
            'defaults' => [
                'verify' => false
            ],
            'timeout' => 10.0,
        ]);
    }
}
