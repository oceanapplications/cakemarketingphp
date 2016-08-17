<?php

namespace OceanApplications\CakeMarketing;

class CakeMarketing {

    private $base_url;
    private $api_key;
    private $affiliate_id;

    const STATUSALL = 0;
    const STATUSACTIVE = 1;
    const STATUSPUBLIC = 2;
    const STATUSAPPLYTORUN = 3;


    /**
     * @param $domain string domain of network example: http://c2mtrax.com/
     * @param $api_key string API Key from reporting API modal
     * @param $affiliate_id string Affiliate ID from reporting API modal
     */
    function __construct($domain, $api_key, $affiliate_id)
    {
        $this->base_url = $domain."affiliates/api/";
        $this->api_key = "api_key=".$api_key;
        $this->affiliate_id = "affiliate_id=".$affiliate_id;
    }

    /**
     * @param int $media_type_category_id
     * @param int $vertical_category_id
     * @param int $vertical_id
     * @param int $offer_status_id
     * @param int $tag_id
     * @param int $start_at_row
     * @param int $row_limit
     */
    public function OfferFeed($media_type_category_id=0, $vertical_category_id=0, $vertical_id=0,
                              $offer_status_id=0, $tag_id=0, $start_at_row=0, $row_limit=0, $campaign_name="") {
        $url = $this->base_url."2/offers.asmx/OfferFeed?$this->api_key&$this->affiliate_id&media_type_category_id=".
            $media_type_category_id."&vertical_category_id=$vertical_category_id&vertical_id=$vertical_id&offer_status_id=$offer_status_id".
            "&tag_id=$tag_id&start_at_row=$start_at_row&row_limit=$row_limit&campaign_name=$campaign_name";

        $offersXML = simplexml_load_string($this->curl_get($url));
        $json = json_encode($offersXML->offers);
        return $json;
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param int $offer_id
     * @param int $currency_id
     * @param string $disposition_type
     * @param string $conversion_type
     * @param string $exclude_bot_traffic
     * @param int $start_at_row
     * @param int $row_limit
     * @return string
     */
    public function Conversions($start_date, $end_date, $offer_id=0, $currency_id=0, $disposition_type="",
        $conversion_type="all", $exclude_bot_traffic="false", $start_at_row=0, $row_limit=0){
        //API v8
        $url = $this->base_url."8/reports.asmx/Conversions?$this->api_key&$this->affiliate_id&start_date=$start_date";
        $url .= "&end_date=$end_date&offer_id=$offer_id&currency_id=$currency_id&disposition_type=$disposition_type&conversion_type=$conversion_type";
        $url .= "&exclude_bot_traffic=$exclude_bot_traffic&start_at_row=$start_at_row&row_limit=$row_limit";

        $offersXML = simplexml_load_string($this->curl_get($url));

        $json = json_encode($offersXML->conversions);
        return $json;
    }

    /**
     * @param $path URL to send HTTP GET request to
     * @return string JSON encoded response
     */
    private function curl_get($path)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Ruby');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
