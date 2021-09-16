<?php

namespace TBMintbase\Model;

use TBMintbase\Model\Interfaces\IStore;

class StoreModel implements IStore
{
    const DEFAULT_BASE_URI = 'https://arweave.net';

    public $id;

    public $name;

    public $baseUri;

    public $owner;

    public $minters = [];

    public $things = [];

    public function __construct($id, $baseUri, $owner, $name, $minters = [], $things = [])
    {
        $this->id = $id;
        $this->baseUri = $baseUri;
        $this->owner = $owner;
        $this->name = $name;
        $this->minters = $minters;
        $this->prepareThings($things);
    }


    public static function createStoreFromData(array $data): StoreModel
    {
        try {
            $id = $data['id'] ?? null;
            $baseUri = isset($data['baseUri']) ? $data['baseUri'] : self::DEFAULT_BASE_URI;
            $minters = isset($data['minters']) ? $data['minters'] : [];
            $things = isset($data['things']) ? $data['things'] : [];

            if (!isset($data['name'])) {
                throw new \Exception(__('Empty name', 'TBMintbase'));
            }
            if (!isset($data['owner'])) {
                throw new \Exception(__('Empty owner', 'TBMintbase'));
            }
            return new StoreModel($id, $baseUri, $data['owner'], $data['name'], $minters, $things);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    protected function prepareThings(array $things) :void
    {
        if (!empty($things)) {
            foreach ($things as $thing) {
                $this->things[] = new ThingModel($thing->id, $thing->metaId, $thing->memo, $this->baseUri);
            }
        }
    }
}
