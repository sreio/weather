<?php 
namespace Xw\Weather;

use GuzzleHttp\Client;
use Xw\Weather\Exceptions\HttpException;
use Xw\Weather\Exceptions\ParamException;

/**
 * 
 */
class Weather
{
	
	// 高德天气API Key
	protected $key;

	//GuzzleHttp 参数
	protected $guzzleOptions = [];

	//高德API请求接口地址
	protected $apiUrl = 'https://restapi.amap.com/v3/weather/weatherInfo';

	function __construct(string $key)
	{
		$this->key = $key;
	}

    /**
     * @return Client
     */
	public function getHttpClient()
	{
		return new Client($this->guzzleOptions);
	}

    /**
     * @param array $options
     */
	public function setGuzzleOptions(array $options)
	{
		$this->guzzleOptions = $options;
	}

    /**
     * @param $city
     * @param string $type
     * @param string $format
     * @return mixed|string
     */
    public function weather($city, string $type = 'live', string $format = 'json')
    {
        $types = [
            'live' => 'base',
            'forecast' => 'all'
        ];

        if (!\array_key_exists(\strtolower($type), $types)) {
            throw new ParamException("Invalid type value(live/forecast):" . $type);
        }

        if (!\in_array(\strtolower($format), ['json','xml'])) {
            throw new ParamException("Invalid response format:" . $format);
        }

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => \strtolower($format),
            'extensions' => \strtolower($type)
        ]);

        try{
            $response = $this->getHttpClient()->get($this->apiUrl, [
                'query' => $query
            ])->getBody()->getContents();

            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param $city
     * @param string $format
     * @return mixed
     */
    public function getLiveWeather($city, $format = 'json')
    {
        return $this->weather($city, 'live', $format);
    }

    /**
     * @param $city
     * @param string $format
     * @return mixed
     */
    public function getForecastsWeather($city, $format = 'json')
    {
        return $this->weather($city, 'forecast', $format);
    }
}
 ?>