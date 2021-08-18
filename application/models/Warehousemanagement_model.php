<?php
class Warehousemanagement_model extends CI_Model
{

    public function BulkRegisterwarehouse($account_id)
    {
        // echo "Account Id ". $account_id;
        // die();
        $tpa_data = $this->db->where('account_id',$account_id)->get('master_transitpartners_accounts')->row();
        $total_address = $this->db->query("SELECT * FROM users_address where registered_status='0'")->num_rows();
        $reg_address = 0;
        foreach ($this->db->where('registered_status','0')->get('users_address')->result() as $unreg_add)
        {
            $reg_address++;
            //Add Account IDs for Delhivery
            if($tpa_data->parent_id=='1')
            {
                $address_data = array(
                    'phone' =>$unreg_add->phone,
                    "city"  =>$unreg_add->address_city,
                    "name"  =>$unreg_add->address_title,
                    "pin"   =>$unreg_add->pincode,
                    "address"=>$unreg_add->full_address,
                    "country"=>"India",
                    "email"=>"",
                    "registered_name"=>$unreg_add->address_title,
                    "return_address"=>$unreg_add->full_address,
                    "return_pin"=>$unreg_add->pincode,
                    "return_city"=>$unreg_add->address_city,
                    "return_state"=>$unreg_add->address_state,
                    "return_country"=>"India"
                );
        
                $fields=json_encode($address_data);

                $headers = array
                (
                    'Authorization: Token '.$tpa_data->account_key,
                    'Content-Type: application/json'
                );

                // _print_r($fields);
                //Inserting API Log
                $apilogs_data = array(
                    'partner'       => 'Delhivery',
                    'event_name'    => 'AddWarehouse-Bulk',
                    'event_id'      => $tpa_data->account_name,
                    'payload'       => $fields,
                );
                $apilog_id = $this->insertions_model->insert('tbl_pushedapilogs',$apilogs_data);

                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://staging-express.delhivery.com/api/backend/clientwarehouse/create/' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
                $result = curl_exec($ch);
                curl_close($ch);
                $xml = simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
                $response = json_decode(json_encode((array)$xml), TRUE);
                // _print_r($response);
                // die();
                //Updating Response in APILog
                $this->updations_model->update('tbl_pushedapilogs',['apilog_id'=>$apilog_id],['response'=>json_encode($response)]);
                if(!empty($xml) && !empty($response))
                {
                    if($response['success']=="False")
                        $this->db->query("UPDATE users_address SET api_response = CONCAT(COALESCE(api_response,''), '".$response['error']['list-item']."..! ') WHERE address_title='".$unreg_add->address_title."'");
                    else
                        $this->db->query("UPDATE users_address SET registered_status = '1', api_response = CONCAT(COALESCE(api_response,''), 'Warehouse Registered with $tpa_data->account_name..! ') WHERE address_title='".$unreg_add->address_title."'");
                }
            }
            //Add Account IDs for Udaan
            if($tpa_data->parent_id=='4')
            {
                $address_data = array(
                    'orgUnitId'     => '',
                    "addressLine1"  => $unreg_add->full_address,
                    "addressLine2"  => "",
                    "addressLine3"  => "",
                    "city"          => $unreg_add->address_city,
                    "state"         => $unreg_add->address_state,
                    "pincode"       => $unreg_add->pincode,
                    "unitName"      => $unreg_add->address_title,
                    "representativeName" => $unreg_add->addressee,
                    'mobileNumber' =>$unreg_add->phone,
                );
                $fields=json_encode($address_data);

                $headers = array
                (
                    'Authorization:'.$tpa_data->account_key,
                    'Content-Type: application/json',
                    'cf-access-client-id:'.$tpa_data->account_username,
                    'cf-access-client-secret:'.$tpa_data->account_secret
                );
                // _print_r($fields);

                //Inserting API Log
                $apilogs_data = array(
                    'partner'       => 'Udaan',
                    'event_name'    => 'AddWarehouse-Bulk',
                    'event_id'      => $tpa_data->account_name,
                    'payload'       => $fields,
                );
                $apilog_id = $this->insertions_model->insert('tbl_pushedapilogs',$apilogs_data);

                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL,'https://dev.udaan.com/api/udaan-express/integration/v1/address/'.$tpa_data->account_description);
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields );
                $result = curl_exec($ch);
                curl_close($ch);
                
                $result_wh = json_decode($result);
                // _print_r($result_wh);
                // die();
                //Updating Response in APILog
                $this->updations_model->update('tbl_pushedapilogs',['apilog_id'=>$apilog_id],['response'=>json_encode($result_wh)]);

                if(!empty($result_wh) && !empty($result_wh->response) && $result_wh->responseCode=="UE_1001")
                    $this->db->query("UPDATE users_address SET vendor_add_id ='".$result_wh->response->orgUnitId."', registered_status = '1', api_response = CONCAT(COALESCE(api_response,''), 'Warehouse Registered with $tpa_data->account_name..! ') WHERE address_title='".$unreg_add->address_title."'");
                else
                    $this->db->query("UPDATE users_address SET api_response = CONCAT(COALESCE(api_response,''), '".$result_wh->responseMessage."..! ') WHERE address_title='".$unreg_add->address_title."'");
            }
        }

        $return_data = array(
            'status' => 'success',
            'tot_add' => $total_address,
            'reg_add' => $reg_address
        );

        return $return_data;
    }
    
}
?>