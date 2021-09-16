<?php

namespace TBMintbase\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MintBaseModel
{
    protected $graphqlLink = 'https://mintbase-mainnet.hasura.app/v1/graphql';

    protected function getStoreQueryById($storeId, $limit = 20, $offset = 0): string
    {
        $query = '
         query {
            store(where: { id: { _eq:"'.$storeId.'" } }) {
              id
              name
              symbol
              baseUri
              owner
              minters {
                account
                enabled
              }
              things(limit: 20, offset:0) {
                id
                memo
                metaId
                tokens_aggregate {
                  aggregate {
                    count
                  }
                }
                tokens(limit: 1, offset: 0) {
                  id
                  minter
                  royaltys {
                    account
                    percent
                  }
                  splits {
                    account
                    percent
                  }
                }
              }
            }
          }
       
       ';
        return $query;
    }

    public function getThingQueryById($thingId)
    {
        $query = '
        query {
          thing(where: {id: {_eq: "'.$thingId.'"}}) {
            id
            memo
            metaId
            tokens {
              id
              ownerId
            }
              store {
                baseUri
                owner
                reference
              }
          }
        }
        ';
        return $query;
    }

    public function getStoreById($storeId)
    {
        try {
            $result = $this->getClient()->request(
                'POST',
                "$this->graphqlLink",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'query' =>  $this->getStoreQueryById($storeId),
                    ]),
                ]
            );
            $data = (array)json_decode((string)$result->getBody());
            if (isset($data['data']) && isset($data['data']->store) && isset($data['data']->store[0])) {
                return StoreModel::createStoreFromData((array)$data['data']->store[0]);
            }
        } catch (RequestException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getThingById($id)
    {
        try {
            $result = $this->getClient()->request(
                'POST',
                "$this->graphqlLink",
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'query' =>  $this->getThingQueryById($id),
                    ]),
                ]
            );
            $data = (array)json_decode((string)$result->getBody());
            if (isset($data['data']) && isset($data['data']->thing) && isset($data['data']->thing[0])) {
                $thing = $data['data']->thing[0];
                return new ThingModel($thing->id, $thing->metaId, $thing->memo, $thing->store->baseUri);
            }
        } catch (RequestException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Guzzle connection to api
     *
     * @return Client
     */
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

