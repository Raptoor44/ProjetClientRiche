<?php
/*
 * Copyright 2013 Jan Eichhorn <exeu65@googlemail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace ApaiIO\Request;

use ApaiIO\ApaiIO;
use ApaiIO\Configuration\ConfigurationInterface;
use ApaiIO\Operations\OperationInterface;
use ApaiIO\Request\GuzzleRequest;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Uri;

/**
 * Basic implementation of the rest request
 *
 * @see    http://docs.aws.amazon.com/AWSECommerceService/2011-08-01/DG/AnatomyOfaRESTRequest.html
 * @author Jan Eichhorn <exeu65@googlemail.com>
 */
class GuzzleRequestWithoutKeys extends GuzzleRequest
{   
   /**
     * The requestscheme
     *
     * @var string
     */
    protected $requestTemplate = "http://odp.tuxfamily.org/onca/xml?Country=%s&%s";
	
    /**
     * The scheme for the uri. E.g. http or https.
     *
     * @var string
     */
    protected $scheme = 'http';
	
    /**
     * @var ClientInterface
     */
    protected $client;
	
    /**
     * Initialize instance
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
		parent::__construct($client) ;
        $this->client = $client;
    }
    
	/**
     * {@inheritdoc}
     */
    public function perform(OperationInterface $operation, ConfigurationInterface $configuration)
    {
        $preparedRequestParams = $this->prepareRequestParams($operation, $configuration);
        $queryString = $this->buildQueryString($preparedRequestParams, $configuration);

        $uri = new Uri(sprintf($this->requestTemplate, $configuration->getCountry(), $queryString));
        $request = new \GuzzleHttp\Psr7\Request('GET', $uri->withScheme($this->scheme), [
            'User-Agent' => 'ApaiIO [' . ApaiIO::VERSION . ']'
        ]);
		
		$options = array(
            RequestOptions::CONNECT_TIMEOUT => 30,
            RequestOptions::TIMEOUT         => 30
        );
		
        $result = $this->client->send($request,$options);

        return $result->getBody()->getContents();
    }
}
