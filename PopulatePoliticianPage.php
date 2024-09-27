add_shortcode('data-display', 'custom_api_data_display');
function custom_api_data_display() {

    $userId = get_current_user_id();
    
    error_log('POPULATE PAGE: current user id is'.$userId);
    
    if ($userId > 0) // Only populate the page if the user is logged in, i.e., the user has an actual id
    {
        // If the access token exists, get data from the external API
        $external_api_user_id = get_user_meta($userId, 'playerId', true); // false, only return single
        if (empty($external_api_user_id))
        {
            $external_api_user_id = 'd0887ef9-492c-4a62-b5fa-ec69d5e696c5'; // Set a mock value
            error_log('POPULATE PAGE: Set a mock value since no stored playerId was found');
        }
        
        error_log('POPULATE PAGE: We got the playerId '.$external_api_user_id.' for user'.$userId);    
        
        $response = wp_remote_get('https://xstdkgrsy.execute-api.us-east-1.amazonaws.com/GetPoliticianProfileData?id='.$external_api_user_id); //API Address changed

        if (is_wp_error($response)) 
        {
            error_log('POPULATE PAGE: Problem fetching politician data');    
            return '<p>There was a problem fetching politician data.</p>';
        } 
        else 
        {
            $body = wp_remote_retrieve_body($response);

            $data = json_decode($body);
            $message = $data->message;

            $html = '<h6>PROFILE</h6>';

            $html .= '<p>Name: '.$message->pName. '<br>';
            $html .= 'State: '.$message->pState. '<br>';
            $html .= 'Party: '.$message->party. '<br>';
            $html .= 'Caucus: - <br>';
            $html .= 'Social-Beliefs: '.$message->beliefs. '<br>';
            $html .= 'Social-Enforcement: - <br>';
            $html .= 'Economic-Ideology: '.$message->ideology. '<br>';
            $html .= 'Education: '.$message->education. '<br>';
            $html .= 'Age: '.$message->age. '<br>';
            $html .= 'Race: '.$message->race. '<br>';
            $html .= 'Sexual Orientation: '.$message->sexualOrientation. '<br>';
            $html .= 'Gender: '.$message->gender. '<br>';

            $html .= '</p><p>';
            $html .= 'Discord Username: - <br>';
            $html .= 'Last Online: '.$message->lastActivityTimePhrase. '<br>';
            $html .= 'Account Age: '.$message->accountAgePhrase. '<br>';

            $html .= '</p><p>';

            $html .= 'Local Influence: '.$message->localInfluence. '<br>';
            $html .= 'National Influence: '.$message->nationalInfluence. '<br>';
            $html .= 'Business Influence: '.$message->businessInfluence. '<br>';
            $union = 'Uni';

            $html .= $union.'on Influence: '.$message->unionInfluence. '<br>';        
            $html .= 'Media Influence: '.$message->mediaInfluence. '<br>';
            $html .= 'Controversy: '.$message->controversy. '<br>';
            $html .= 'Infamy: '.$message->infamy. '<br>';
            $html .= 'Notoriety: '.$message->notoriety. '<br>';

            $html .= '</p><p>';

            $html .= 'Political Capital: '.$message->politicalCapital. '<br>';
            $html .= 'Campaign Finances: '.$message->campaignFinances. '<br>';
            $html .= 'Cash: '.$message->cash. '<br>';
            $html .= 'Gold: '.$message->gold. '<br>';
            $html .= 'Stocks: '.$message->stocks. '<br>';
            $html .= 'Bonds and Investments: '.$message->bondsAndInvestments. '<br>';

            $html .= '</p>';

            return $html;
        }
    }
}
