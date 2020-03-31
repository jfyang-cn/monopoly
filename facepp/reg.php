<?php
require_once 'facepp_sdk.php';
########################
###     example      ###
########################
$facepp = new Facepp();
$facepp->api_key       = '97a4207888bab3aa50523ae2a8032f78';
$facepp->api_secret    = 'fQsSA66lJ2y7p56oJN2Fa60oTOdIQ-u-';

#detect local image 
$params['img']          = '../asset/images/avatar/face2.png';
$params['attribute']    = 'gender,age,race,smiling,glass,pose';

$response               = $facepp->execute('/detection/detect',$params);
print_r($response);

//#detect image by url
//$params['url']          = 'http://www.faceplusplus.com.cn/wp-content/themes/faceplusplus/assets/img/demo/1.jpg';
//$response               = $facepp->execute('/detection/detect',$params);
//print_r($response);

//if($response['http_code'] == 200) {
//    #json decode 
//    $data = json_decode($response['body'], 1);
    
//    #get face landmark
//    foreach ($data['face'] as $face) {
//        $response = $facepp->execute('/detection/landmark', array('face_id' => $face['face_id']));
//        print_r($response);
//    }
    
//    #create person 
//    $response = $facepp->execute('/person/create', array('person_name' => 'unique_person_name'));
//    print_r($response);

//    #delete person
//    $response = $facepp->execute('/person/delete', array('person_name' => 'unique_person_name'));
//    print_r($response);

//}
?>
