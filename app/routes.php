<?php
//Hpme Route
$app->get('/', App\Action\HomeAction::class)
    ->setName('Velocity API');
//System
include_once dirname(__FILE__). '/../app/src/system/System.php';
include_once dirname(__FILE__). '/../app/src/system/Admin.php';
include_once dirname(__FILE__). '/../app/src/system/User.php';
include_once dirname(__FILE__). '/../app/src/system/SMS.php';
include_once dirname(__FILE__). '/../app/src/system/cronjobs/Match.php';

$op = new System();
$admin = new Admin();
$user = new User();
$sms = new SMS();
$match = new Match();
$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/../../public_html/home/uploads/images/';

$app->get('/test', function ($request, $response)use($match){
    return $response ->withJson($match->allListing());
});
//user and app routes
$app->group('/api/user', function ($group)use($op, $user){



    $group->post('/login', function ($request, $response)use($user){
        $params = $request->getParsedBody();
        $pwd = $user->validateParameter('Password',$params['password'], STRING);
        $msisdn = $user->validateParameter('Mobile', $params['msisdn'], 'mobile');

        if($msisdn['success'] == true){
            $user->setMsisdn($msisdn['data']);
        }else{
            return $response
                ->withJson($msisdn)
                ->withStatus($msisdn['statusCode']);
        }

        if ($pwd['success']){
            $user->setPassword($pwd['data']);
        }else{
            return $response
                ->withJson($pwd)
                ->withStatus($pwd['statusCode']);
        }

        $login = $user->login();
        return $response
            ->withJson($login)
            ->withStatus($login['statusCode']);
    });

    $group->put('/register', function($request, $response)use($user) {
        $params = $request->getParsedBody();
        $pwd = $user->validateParameter('Password',$params['password'], STRING);
        $name = $user->validateParameter('First Name',$params['name'],STRING);
        $lastName = $user->validateParameter('Last Name',$params['surname'],STRING);
        $msisdn = $user->validateParameter('Mobile Number', $params['msisdn'], STRING);
        $country = $user->validateParameter('Country', $params['country'], STRING);
        $countryCode = $user->validateParameter('Country Code', $params['code'], STRING);

        $user->setCountry($country['data']);
        $user->setCountryCode($countryCode['data']);
        if($name['success'] == true){
            $user->setName($name['data']);
        }else{
            return $response
                ->withJson($name)
                ->withStatus($name['statusCode']);
        }

        if($msisdn['success'] == true){
            $user->setMsisdn($msisdn['data']);
        }else{
            return $response
                ->withJson($msisdn)
                ->withStatus($msisdn['statusCode']);
        }

        if ($lastName['success'] == true){
            $user->setLastName($lastName['data']);
        }else{
            return $response
                ->withJson($lastName)
                ->withStatus($lastName['statusCode']);
        }

        if ($pwd['success']){
            if ($pwd['data'] == $params['confirmPassword'])
                $user->setPassword($pwd['data']);
            else
                return $response
                    ->withJson(array(
                        'success' => false,
                        'statusCode' => FORBIDEN,
                        'error' => array('type' => 'REGISTRATION_ERROR', 'message' => "Passwords do not match")
                    ))
                    ->withStatus(FORBIDEN);
        }else{
            return $response
                ->withJson($pwd)
                ->withStatus($pwd['statusCode']);
        }

        $register = $user->Register();

        return $response
            ->withJson($register)
            ->withStatus($register['statusCode']);
    });

    $group->post('/details', function ($request, $response)use($user){
        $params = $request->getParsedBody('token');
        $user->setToken($params['token']);
        $details = $user->getDetails();

        return $response
            ->withJson($details)
            ->withStatus($details['statusCode']);
    });

    $group->post('/activation/code', function ($request, $response)use($user){
        $params = $request->getParsedBody();
        $user->setMsisdn($params['msisdn']);

        $code = $user->getActivationCode();

        return $response
            ->withJson($code)
            ->withStatus($code['statusCode']);
    });

    $group->post('/activate/account', function ($request, $response)use($user){
        $params = $request->getParsedBody();

        $user->setCode($params['code']);
        $user->setMsisdn($params['msisdn']);

        $activation = $user->activateAccount();

        return $response
            ->withJson($activation)
            ->withStatus($activation['statusCode']);

    });

    //change pin
    $group->patch('/settings/password/change', function($request, $response)use($user){
        $params = $request->getParsedBody();
        $pwd = $user->validateParameter('Password',$params['password'], STRING);
        $newPassword = $user->validateParameter('New Password',$params['newPassword'], STRING);
        $user->setToken($params['token']);

        if ($pwd['success']){
            $user->setPassword($pwd['data']);
        }else{
            return $response
                ->withJson($pwd)
                ->withStatus($pwd['statusCode']);
        }

        if ($newPassword['success']){
            $user->setNewPassword($params['newPassword']);
        }else{
            return $response
                ->withJson($newPassword)
                ->withStatus($newPassword['statusCode']);
        }

        $data = $user->changePassword();
        return $response
            ->withJson($data)
            ->withStatus($data['statusCode']);
    });

    //listings routes
    $group->put('/save/listings/{category}', function ($request, $response)use($user){
        $type = $request->getAttribute('category');
        switch ($type){
            case "1":
                //services listing
                $params = $request->getParsedBody();
                    $token = $params['token'];
                    $service = $user->validateParameter("Service ID",$params['service'], INTEGER);
                    $details = $user->validateParameter("Details",$params['details'], STRING);
                    $name = $user->validateParameter("Name",$params['name'], STRING);

                    if ($name['success']){
                        $user->setName($name['data']);
                    }else{
                        return $response
                            ->withJson($name)
                            ->withStatus($name['statusCode']);
                    }

                    if ($service['success']){
                        $user->setId($service['data']);
                    }else{
                        return $response
                            ->withJson($service)
                            ->withStatus($service['statusCode']);
                    }

                    if ($details['success']){
                        $user->setDesc($details['data']);
                    }else{
                        return $response
                            ->withJson($details)
                            ->withStatus($details['statusCode']);
                    }
                    $user->setCategory($type);
                    $user->setToken($token);
                    $subscribe = $user->saveService();
                return $response
                    ->withJson($subscribe)
                    ->withStatus($subscribe['statusCode']);
                break;
            case "2":
                //accommodation
                $params = $request->getParsedBody();
                $token = $params['token'];
                $name = $user->validateParameter("Name", $params['name'], STRING);
                $subcat = $user->validateParameter("Sub Category1", $params['subcategory1'], INTEGER);
                $bedrooms = $user->validateParameter("# of Bedrooms", $params['bedrooms'], INTEGER);
                $dateVacant = $user->validateParameter("Start Date",$params['startDate'], STRING);
                $price = $user->validateParameter("Rent Price", $params['price'], STRING);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $country = $user->validateParameter("Country",$params['country'], STRING);
                $image = $user->validateParameter("Thumbnail", $params['thumbnail'], STRING);
                $notes = $user->validateParameter("Notes", $params['notes'], STRING);

                $user->setCategory($type);
                $user->setToken($token);
                if ($name['success']){
                    $user->setName($name['data']);
                }else{
                    return $response
                        ->withJson($name)
                        ->withStatus($name['statusCode']);
                }

                if ($notes['success']){
                    $user->setDesc($notes['data']);
                }else{
                    return $response
                        ->withJson($notes)
                        ->withStatus($notes['statusCode']);
                }

                if ($image['success']){
                    $user->setThumbnail($image['data']);
                }else{
                    return $response
                        ->withJson($image)
                        ->withStatus($image['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory1($subcat['data']);
                }else{
                    return $response
                        ->withJson($subcat)
                        ->withStatus($subcat['statusCode']);
                }

                if ($bedrooms['success']){
                    $user->setBedrooms($bedrooms['data']);
                }else{
                    return $response
                        ->withJson($bedrooms)
                        ->withStatus($bedrooms['statusCode']);
                }

                if ($dateVacant['success']){
                    $user->setDateStart($dateVacant['data']);
                }else{
                    return $response
                        ->withJson($dateVacant)
                        ->withStatus($dateVacant['statusCode']);
                }

                if ($town['success']){
                    $user->setTown($town['data']);
                }else{
                    return $response
                        ->withJson($town)
                        ->withStatus($town['statusCode']);
                }

                if ($country['success']){
                    $user->setCountry($country['data']);
                }else{
                    return $response
                        ->withJson($country)
                        ->withStatus($country['statusCode']);
                }

                if ($price['success']){
                    $user->setPrice($price['data']);
                }else{
                    return $response
                        ->withJson($price)
                        ->withStatus($price['statusCode']);
                }


                $accomo = $user->saveAccommodation();
                return $response
                    ->withJson($accomo)
                    ->withStatus($accomo['statusCode']);
                break;
            case "3":
                //jobs
                $params = $request->getParsedBody();
                $user->setCategory($type);
                $user->setToken($params['token']);
                $level = $user->validateParameter("Job Level", $params['level'], INTEGER);
                $qualification = $user->validateParameter("Job Qualification", $params['qualification'], INTEGER);
                $name = $user->validateParameter("Job title", $params['title'], STRING);
                $subcat = $user->validateParameter("Job Field", $params['field'], INTEGER);
                $subcat2 = $user->validateParameter("Job Category", $params['category'], INTEGER);
                $notes = $user->validateParameter("Notes", $params['notes'], STRING);
                $deadline = $user->validateParameter("Deadline", $params['deadline'], STRING, false);
                $city = $user->validateParameter('Town', $params['town'], INTEGER, false);
                $country = $user->validateParameter("Country", $params['country'], STRING);

                if ($name['success']){
                    $user->setName($name['data']);
                }else{
                    return $response
                        ->withJson($name)
                        ->withStatus($name['statusCode']);
                }

                if ($level['success']){
                    $user->setJobLevel($level['data']);
                }else{
                    return $response
                        ->withJson($level)
                        ->withStatus($level['statusCode']);
                }

                if ($qualification['success']){
                    $user->setJobQualification($qualification['data']);
                }else{
                    return $response
                        ->withJson($qualification)
                        ->withStatus($qualification['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory1($subcat['data']);
                }else{
                    return $response
                        ->withJson($subcat)
                        ->withStatus($subcat['statusCode']);
                }

                if ($subcat2['success']){
                    $user->setSubcategory2($subcat2['data']);
                }else{
                    return $response
                        ->withJson($subcat2)
                        ->withStatus($subcat2['statusCode']);
                }

                if ($deadline['success']){
                    $user->setDeadline($deadline['data']);
                }else{
                    return $response
                        ->withJson($deadline)
                        ->withStatus($deadline['statusCode']);
                }

                if ($city['success']){
                    $user->setTown($city['data']);
                }else{
                    return $response
                        ->withJson($city)
                        ->withStatus($city['statusCode']);
                }

                if ($notes['success']){
                    $user->setDesc($notes['data']);
                }else{
                    return $response
                        ->withJson($notes)
                        ->withStatus($notes['statusCode']);
                }

                if ($country['success']){
                    $user->setCountry($country['data']);
                }else{
                    return $response
                        ->withJson($country)
                        ->withStatus($country['statusCode']);
                }

                $jobs = $user->saveJobs();
                return $response
                    ->withJson($jobs)
                    ->withStatus($jobs['statusCode']);

                break;
            case "4":
                //vehicle
                $user->setCategory($type);
                $params = $request->getParsedBody();
                $token  = $params['token'];
                $name = $user->validateParameter("Name", $params['name'], STRING);
                $subcat = $user->validateParameter("Mode of transport medium", $params['mode'], INTEGER);
                $subcat2 = $user->validateParameter("Type of vehicle", $params['type'], INTEGER);
                $subcat3 = $user->validateParameter("Vehicle Model", $params['model'], INTEGER);
                $brand = $user->validateParameter("Vehicle brand", $params['brand'], INTEGER);
                $fuel = $user->validateParameter("Vehicle fuel", $params['fuel'], STRING);
                $transmission = $user->validateParameter("Transmission", $params['transmission'], STRING);
                $description = $user->validateParameter("Notes", $params['notes'], STRING);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $image = $user->validateParameter("Thumbnail", $params['thumbnail'], STRING);
                $price = $user->validateParameter("Price", $params['price'], STRING);

                if ($subcat3['success']){
                    $user->setModel($name['data']);
                }else{
                    return $response
                        ->withJson($subcat3)
                        ->withStatus($subcat3['statusCode']);
                }

                if ($name['success']){
                    $user->setName($name['data']);
                }else{
                    return $response
                        ->withJson($name)
                        ->withStatus($name['statusCode']);
                }
                if ($brand['success']){
                    $user->setBrand($brand['data']);
                }else{
                    return $response
                        ->withJson($brand)
                        ->withStatus($brand['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory1($subcat['data']);
                }else{
                    return $response
                        ->withJson($subcat)
                        ->withStatus($subcat['statusCode']);
                }

                if ($subcat2['success']){
                    $user->setSubcategory2($subcat2['data']);
                }else{
                    return $response
                        ->withJson($subcat2)
                        ->withStatus($subcat2['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory3($subcat3['data']);
                }else{
                    return $response
                        ->withJson($subcat3)
                        ->withStatus($subcat3['statusCode']);
                }

                if ($fuel['success']){
                    $user->setVehicleFuel($fuel['data']);
                }else{
                    return $response
                        ->withJson($fuel)
                        ->withStatus($fuel['statusCode']);
                }

                if ($transmission['success']){
                    $user->setVehicleTransmission($transmission['data']);
                }else{
                    return $response
                        ->withJson($transmission)
                        ->withStatus($transmission['statusCode']);
                }

                if ($description['success']){
                    $user->setDesc($description['data']);
                }else{
                    return $response
                        ->withJson($description)
                        ->withStatus($description['statusCode']);
                }

                if ($town['success']){
                    $user->setTown($town['data']);
                }else{
                    return $response
                        ->withJson($town)
                        ->withStatus($town['statusCode']);
                }

                if ($price['success']){
                    $user->setPrice($price['data']);
                }else{
                    return $response
                        ->withJson($price)
                        ->withStatus($price['statusCode']);
                }

                if ($image['success']){
                    $user->setThumbnail($image['data']);
                }else{
                    return $response
                        ->withJson($image)
                        ->withStatus($image['statusCode']);
                }
                $user->setToken($token);

                $vehicle = $user->saveVehicle();
                return $response
                    ->withJson($vehicle)
                    ->withStatus($vehicle['statusCode']);
                break;
            default:
                return $response
                    ->withJson(
                        array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => 'Invalid parameters')
                        )
                    )
                    ->withStatus(INTERNAL_SERVER_ERROR);
                break;
        }

    });

    $group->post('/upload/thumbnail/{id}', function ($request, $response)use($user){
        $directory = $this->get('upload_directory');
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['file'];
        $path = $directory."/thumbnails/";
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile($path, $uploadedFile);

            $url = "/thumbnails/".$filename;
            $user->setThumbnail($url);
            return $response->withJson(
                array(
                    'success' => true,
                    'statusCode' => SUCCESS_RESPONSE,
                    'error'=> array(
                        'type' => "UPLOAD_ERROR",
                        'message' => "Upload Complete"
                    )
                )
            )->withStatus(200);
        }else{

            return $response->withJson(
                array(
                    'success' => false,
                    'statusCode' => FORBIDEN,
                    'error'=> array(
                        'type' => "UPLOAD_ERROR",
                        'message' => "Failed to upload image"
                    )
                )
            )->withStatus(200);

        }
    });

    $group->put('/subscribe/requests/{category}', function ($request, $response)use($user){
        $type = $request->getAttribute('category');
        switch ($type){
            case "1":
                $params = $request->getParsedBody();
                $token = $params['token'];
                $name= $params['name'];
                $service = $params['service_id'];
                $user->setCategory($type);
                $user->setId($service);
                $user->setName($name);
                $user->setToken($token);
                $subscribe = $user->serviceSubscribe();
                return $response
                    ->withJson($subscribe)
                    ->withStatus($subscribe['statusCode']);
                break;
            case "2":
                //accommodation request

                $params = $request->getParsedBody();
                $subcat = $user->validateParameter("Sub Category1", $params['subcategory1'], INTEGER);
                $bedrooms = $user->validateParameter("# of Bedrooms", $params['bedrooms'], INTEGER);
                $dateVacant = $user->validateParameter("Start Date",$params['startDate'], STRING);
                $price = $user->validateParameter("Rent Start Price", $params['price'], MYSQLI_TYPE_DECIMAL);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $country = $user->validateParameter("Country",$params['country'], STRING);
                $price2= $user->validateParameter('Rent End Price', $params['priceRange'], MYSQLI_TYPE_DECIMAL);


                $user->setCategory($type);
                $user->setToken($params['token']);
                if ($subcat['success']){
                    $user->setSubcategory1($subcat['data']);
                }else{
                    return $response
                        ->withJson($subcat)
                        ->withStatus($subcat['statusCode']);
                }

                if ($bedrooms['success']){
                    $user->setBedrooms($bedrooms['data']);
                }else{
                    return $response
                        ->withJson($bedrooms)
                        ->withStatus($bedrooms['statusCode']);
                }

                if ($dateVacant['success']){
                    $user->setDateStart($dateVacant['data']);
                }else{
                    return $response
                        ->withJson($dateVacant)
                        ->withStatus($dateVacant['statusCode']);
                }

                if ($town['success']){
                    $user->setTown($town['data']);
                }else{
                    return $response
                        ->withJson($town)
                        ->withStatus($town['statusCode']);
                }

                if ($country['success']){
                    $user->setCountry($country['data']);
                }else{
                    return $response
                        ->withJson($country)
                        ->withStatus($country['statusCode']);
                }

                if ($price['success']){
                    $user->setPrice($price['data']);
                }else{
                    return $response
                        ->withJson($price)
                        ->withStatus($price['statusCode']);
                }

                if ($price2['success']){
                    $user->setPrice2($price['data']);
                }else{
                    return $response
                        ->withJson($price2)
                        ->withStatus($price2['statusCode']);
                }

                $accomodation = $user ->accommodationSubscribe();
                return $response
                    ->withJson($accomodation)
                    ->withStatus($accomodation['statusCode']);
                break;
            case "3":
                //job request
                $params = $request->getParsedBody();
                $user->setCategory($type);
                $user->setToken($params['token']);
                $level = $user->validateParameter("Job Level", $params['level'], INTEGER);
                $qualification = $user->validateParameter("Job Qualification", $params['qualification'], INTEGER);
                $subcat = $user->validateParameter("Job Field", $params['field'], INTEGER);
                $subcat2 = $user->validateParameter("Job Category", $params, INTEGER);
                $city = $user->validateParameter('Town', $params['town'], STRING);
                $country = $user->validateParameter("Country", $params['country'], STRING);


                if ($level['success']){
                    $user->setJobLevel($level['data']);
                }else{
                    return $response
                        ->withJson($level)
                        ->withStatus($level['statusCode']);
                }

                if ($qualification['success']){
                    $user->setJobQualification($qualification['data']);
                }else{
                    return $response
                        ->withJson($qualification)
                        ->withStatus($qualification['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory1($subcat['data']);
                }else{
                    return $response
                        ->withJson($subcat)
                        ->withStatus($subcat['statusCode']);
                }

                if ($subcat2['success']){
                    $user->setSubcategory2($subcat2['data']);
                }else{
                    return $response
                        ->withJson($subcat2)
                        ->withStatus($subcat2['statusCode']);
                }

                if ($city['success']){
                    $user->setTown($city['data']);
                }else{
                    return $response
                        ->withJson($city)
                        ->withStatus($city['statusCode']);
                }

                if ($country['success']){
                    $user->setCountry($country['data']);
                }else{
                    return $response
                        ->withJson($country)
                        ->withStatus($country['statusCode']);
                }

                $jobsSubs = $user->jobSubscription();
                return $response
                    ->withJson($jobsSubs)
                    ->withStatus($jobsSubs['statusCode']);
                break;
            case "4":
                $user->setCategory($type);
                $params = $request->getParsedBody();
                $subcat = $user->validateParameter("Mode of transport medium", $params['mode'], INTEGER);
                $name = $user->validateParameter("Name", $params['name'], STRING);
                $subcat2 = $user->validateParameter("Type of vehicle", $params['type'], INTEGER);
                $subcat3 = $user->validateParameter("Vehicle Model", $params['vehicle'], INTEGER);
                $fuel = $user->validateParameter("Vehicle fuel", $params['fuel'], STRING);
                $transmission = $user->validateParameter("Transmission", $params['transmission'], STRING, false);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $price = $user->validateParameter("Price", $params['price'], MYSQLI_TYPE_DECIMAL);
                $price2 = $user->validateParameter('Top Price',$params['priceRange'], MYSQLI_TYPE_DECIMAL);
                $country = $user->validateParameter("Country", $params['country'], STRING);

                if ($name['success']){
                    $user->setName($name['data']);
                }else{
                    return $response
                        ->withJson($name)
                        ->withStatus($name['statusCode']);
                }

                if ($country['success']){
                    $user->setCountry($country['data']);
                }else{
                    return $response
                        ->withJson($country)
                        ->withStatus($country['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory1($subcat['data']);
                }else{
                    return $response
                        ->withJson($subcat)
                        ->withStatus($subcat['statusCode']);
                }

                if ($subcat2['success']){
                    $user->setSubcategory2($subcat2['data']);
                }else{
                    return $response
                        ->withJson($subcat2)
                        ->withStatus($subcat2['statusCode']);
                }

                if ($subcat['success']){
                    $user->setSubcategory3($subcat3['data']);
                }else{
                    return $response
                        ->withJson($subcat3)
                        ->withStatus($subcat3['statusCode']);
                }

                if ($fuel['success']){
                    $user->setVehicleFuel($fuel['data']);
                }else{
                    return $response
                        ->withJson($fuel)
                        ->withStatus($fuel['statusCode']);
                }

                if ($transmission['success']){
                    $user->setVehicleTransmission($transmission['data']);
                }else{
                    return $response
                        ->withJson($transmission)
                        ->withStatus($transmission['statusCode']);
                }


                if ($town['success']){
                    $user->setTown($town['data']);
                }else{
                    return $response
                        ->withJson($town)
                        ->withStatus($town['statusCode']);
                }

                if ($price2['success']){
                    $user->setPrice2($price2['data']);
                }else{
                    return $response
                        ->withJson($price2)
                        ->withStatus($price2['statusCode']);
                }

                if ($price['success']){
                    $user->setPrice($price['data']);
                }else{
                    return $response
                        ->withJson($price)
                        ->withStatus($price['statusCode']);
                }

                $vehicle = $user->vehicleSubscribe();
                return $response
                    ->withJson($vehicle)
                    ->withStatus($vehicle['statusCode']);
                break;
            default:
                return $response
                    ->withJson(
                        array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => 'Invalid parameters')
                        )
                    )
                    ->withStatus(INTERNAL_SERVER_ERROR);
                break;
        }

    });

    $group->delete('/remove/{type}/{id}', function ($request, $response)use($user){
        $type = $request->getAttribute('type');
        $params = $request->getParsedBody();
        $user->setToken($params['token']);
        switch ($type){
            case 'listing':
                $id = $request->getAttribute('id');
                $user->setId($id);

                $delete = $user->removeListing();

                return $response
                    ->withJson($delete)
                    ->withStatus($delete['statusCode']);
                break;
            case 'request';
                $id = $request->getAttribute('id');
                $user->setId($id);

                $delete = $user->removeRequest();

                return $response
                    ->withJson($delete)
                    ->withStatus($delete['statusCode']);
            break;

            default:
                return $response
                    ->withJson(
                        array(
                            'success' => false,
                            'statusCode' => INTERNAL_SERVER_ERROR,
                            'error' => array('type' => 'PROCESS_SERVER_ERROR', 'message' => 'Invalid parameters')
                        )
                    )
                    ->withStatus(INTERNAL_SERVER_ERROR);
                break;
        }
    });

    //matches routes
    $group->post('/my/matches', function($request, $response)use($user){
        $params = $request->getParsedBody();

        $user->setToken($params['token']);
        $matches = $user->myMatches();

        return $response
            ->withJson(array('matches' => $matches))
            ->withStatus($matches['statusCode']);
    });

    $group->post('/my/matches/by/{cat}',function($request, $response)use($user){

    });

    $group->post('/my/matches/details/{id}', function($request, $response)use($user){
        $id = $request->getAttribute("id");
        $params = $request->getParsedBody();

        $user->setToken($params['token']);
        $user->setId($id);
        $matches = $user->matchDetails();

        return $response
            ->withJson($matches)
            ->withStatus($matches['statusCode']);
    });

    $group->post('/my/matches/new', function($request, $response)use($user){
        $params = $request->getParsedBody();

        $user->setToken($params['token']);
        $matches = $user->newMatches();

        return $response
            ->withJson($matches)
            ->withStatus($matches['statusCode']);
    });

    $group->post('/my/match/count', function($request, $response)use($user){
        $params = $request->getParsedBody();
        $user->setToken($params['token']);
        $count = $user->matchCount();
        return $response->withJson(array('count' => $count));
    });

    $group->post('/my/listings', function ($request, $response)use($user){
        $params = $request->getParsedBody();

        $user->setToken($params['token']);
        $matches = $user->userListingsAll();

        return $response
            ->withJson($matches)
            ->withStatus($matches['statusCode']);
    });

    $group->delete('/listing/delete/{id}/{type}', function ($request, $response)use($user){
        $id = $request->getParsedBody();
        $user->setId($id);
        $delete = $user->deleteList();
        return $response->withJson($delete)
            ->withStatus($delete['statusCode']);
    });

});

//system routes
$app->group('/api/system', function ($group)use($op){

    $group->get('/countries',function($request, $response)use($op){
        $countries = $op->Countries();
        return $response
            ->withJson($countries)
            ->withStatus($countries['statusCode']);
    });

    $group->get('/country/states/{country}',function($request, $response)use($op){
        $op->setId($request->getAttribute('country'));
        $countries = $op->States();
        return $response
            ->withJson($countries)
            ->withStatus($countries['statusCode']);
    });

    $group->get('/country/state/cities/{state}',function($request, $response)use($op){
        $op->setId($request->getAttribute('state'));
        $countries = $op->Cities();
        return $response
            ->withJson($countries)
            ->withStatus($countries['statusCode']);
    });

    $group->get('/categories/main',function($request, $response)use($op){
        $categories = $op->Category();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/categories/jobs/levels',function($request, $response)use($op){
        $levels = $op->jobLevels();
        return $response
            ->withJson($levels)
           ->withStatus($levels['statusCode']);
    });

    $group->get('/categories/jobs/qualifications',function($request, $response)use($op){
        $qualifications = $op->jobQualifications();
        return $response
            ->withJson($qualifications)
           ->withStatus($qualifications['statusCode']);
    });

    $group->get('/vehicle/brands/{category}/{type}/{subtype}',function($request, $response)use($op){
        $op->setCategory($request->getAttribute('category'));
        $op->setType($request->getAttribute('type'));
        $op->setModel($request->getAttribute('subtype'));
        $qualifications = $op->vehicleBrands();
        return $response
            ->withJson($qualifications)
           ->withStatus($qualifications['statusCode']);
    });

    $group->get('/categories/sub/higher/{parent}', function($request, $response, array $args)use($op){
        $op->setId($request->getAttribute('parent'));
        $categories = $op->SubCategories1();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/categories/sub/medium/{parent}', function($request, $response, array $args)use($op){
        $op->setId($request->getAttribute('parent'));
        $categories = $op->SubCategories2();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/categories/sub/lower/{parent}', function($request, $response, array $args)use($op){
        $op->setId($request->getAttribute('parent'));
        $categories = $op->SubCategories3();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/type/listings',function($request, $response)use($op){

    });

    $group->get('/type/requests',function($request, $response)use($op){

    });

    $group->get('/listings/{category}', function ($group)use($op){

    });

});

//admin routes
$app->group('/api/admin', function ($group)use($admin){

    $group->get('/categories/main',function($request, $response)use($admin){
        $categories = $admin->Category();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/categories/sub/higher/{parent}', function($request, $response, array $args)use($admin){
        $admin->setId($request->getAttribute('parent'));
        $categories = $admin->SubCategories1();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/categories/sub/medium/{parent}', function($request, $response, array $args)use($admin){
        $admin->setId($request->getAttribute('parent'));
        $categories = $admin->SubCategories2();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->get('/categories/sub/lower/{parent}', function($request, $response, array $args)use($admin){
        $admin->setId($request->getAttribute('parent'));
        $categories = $admin->SubCategories3();
        return $response
            ->withJson($categories)
            ->withStatus($categories['statusCode']);
    });

    $group->post('/login', function ($request, $response)use($admin ){
        $params = $request->getParsedBody();
        $email = $admin->validateParameter('Email', $params['email'], 'email');
        $password = $admin->validateParameter('Password', $params['password'], STRING);

        if ($email['success'] == false){
            return $response
                ->withJson($email)
                ->withStatus($email['statusCode']);
        }

        if ($password['success'] == false){
            return $response->withJson($password)
                ->withStatus($password['statusCode']);
        }
        $admin->setEmail($email['data']);
        $admin->setPassword($password['data']);
        $login = $admin->login();

        return $response
            ->withJson($login)
            ->withStatus($login['statusCode']);
    });

    $group->patch('/update/password', function ($request, $response)use($admin){
        $params = $request->getParsedBody();

        if ($params['password'] !== $params['confirmPassword']){

        }
    });

    $group->put('/create', function($request, $response)use($admin){
        $params = $request->getParsedBody();

        $email = $admin->validateParameter('Email', $params['email'], 'email');
        $password = $admin->validateParameter('Password', $params['password'], STRING);
        $confirmPassword = $admin->validateParameter('Confirm Password', $params['confirmPassword'], STRING);
        $permission = $admin->validateParameter('Account Permissions', $params['permission'], INTEGER);
        $dept = $admin->validateParameter('Department', $params['dept'], INTEGER);

        if ($email['success'] == false){
            return $response
                ->withJson($email)
                ->withStatus($email['statusCode']);
        }

        if ($password['success'] == false){
            return $response
                ->withJson($password)
                ->withStatus($password['statusCode']);
        }

        if ($confirmPassword['success'] == false){
            return $response
                ->withJson($confirmPassword)
                ->withStatus($confirmPassword['statusCode']);
        }

        if ($permission['success'] == false){
            return $response
                ->withJson($permission)
                ->withStatus($permission['statusCode']);
        }

        if ($dept['success'] == false){
            return $response
                ->withJson($dept)
                ->withStatus($dept['statusCode']);
        }

        if ($password['data'] != $confirmPassword['data']) {
            $data =  array('success' => false, 'statusCode' => FORBIDEN, 'error'=> array('type' => "PARAMETER_ERROR", 'message' => 'Password do not match'));
            return $response
                ->withJson($$data)
                ->withStatus($$data['statusCode']);
        }

        $admin->setEmail($email['data']);
        $admin->setPassword($password['data']);
        $admin->setPermissions($permission['data']);
        $admin->setDept($dept['data']);
        $register = $admin->create();

        return $response
            ->withJson($register)
            ->withStatus($register['statusCode']);
    });

    $group->patch('/account/edit', function($request, $response)use($admin){
        $params = $request->getParsedBody();

        $name = $admin->validateParameter('First Name', $params['name'], STRING);
        $lastName = $admin->validateParameter('Last Name', $params['lastName'], STRING);

        if ($name['success'] == false){
            return $response
                ->withJson($name)
                ->withStatus($name['statusCode']);
        }

        if ($lastName['success'] == false){
            return $response
                ->withJson($lastName)
                ->withStatus($lastName['statusCode']);
        }

        $admin->setName($name['data']);
        $admin->setLastName($lastName['data']);
        $save = $admin->adminUpdate();

        return $response
            ->withJson($save)
            ->withStatus($save['statusCode']);
    });

    $group->put('/add/upload/{category}/{level}',function($request, $response)use($admin){
        $params = $request->getParsedBody();
        $admin->setCategory($request->getAttribute('category'));
        $admin->setLevel($request->getAttribute('level'));
        $admin->setFile($params);
        $upload = $admin->addByUpload();
        return $response
            ->withJson($upload);

    });

    $group->put('/add/category', function ($request, $response)use($admin){
        $params = $request->getParsedBody();
        $name = $admin->validateParameter("Name", $params['name'], STRING);
        $desc = $admin->validateParameter("Details", $params['description'], STRING);

        if ($name['success']){
            $admin->setName($name['data']);
        }else{
            return $response
                ->withJson($name)
                ->withStatus($name['statusCode']);
        }

        if ($desc['success']){
            $admin->setDesc($desc['data']);
        }else{
            return $response
                ->withJson($desc)
                ->withStatus($desc['statusCode']);
        }

        $cat = $admin->addCategory();

        return $response
            ->withJson($cat)
            ->withStatus($cat['statusCode']);
    });

    $group->put('/add/subcategory/higher/{parent}', function ($request, $response, array $args)use($admin){

        $admin->setId($request->getAttribute('parent'));
        $params = $request->getParsedBody();
        $name = $admin->validateParameter("Name", $params['name'], STRING);
        $desc = $admin->validateParameter("Details", $params['description'], STRING);

        if ($name['success']){
            $admin->setName($name['data']);
        }else{
            return $response
                ->withJson($name)
                ->withStatus($name['statusCode']);
        }

        if ($desc['success']){
            $admin->setDesc($desc['data']);
        }else{
            return $response
                ->withJson($desc)
                ->withStatus($desc['statusCode']);
        }

        $cat = $admin->addSubCat1();

        return $response
            ->withJson($cat)
            ->withStatus($cat['statusCode']);
    });

    $group->put('/add/subcategory/medium/{parent}', function ($request, $response, array $args)use($admin){
        $admin->setId($request->getAttribute('parent'));
        $params = $request->getParsedBody();
        //if its services no add
        if ($request->getAttribute('parent') == 1 ){
            return $response
                ->withJson(array(
                    'success' => false,
                    'statusCode' => FORBIDEN,
                    'error' => array('type' => 'INTERNAL_SERVER_ERROR', 'message' => 'Not Applicable for Services' )
                ))
                ->withStatus(FORBIDEN);
        }else{
            $name = $admin->validateParameter("Name", $params['name'], STRING);
            $desc = $admin->validateParameter("Details", $params['description'], STRING);

            if ($name['success']){
                $admin->setName($name['data']);
            }else{
                return $response
                    ->withJson($name)
                    ->withStatus($name['statusCode']);
            }

            if ($desc['success']){
                $admin->setDesc($desc['data']);
            }else{
                return $response
                    ->withJson($desc)
                    ->withStatus($desc['statusCode']);
            }

            $cat = $admin->addSubCat2();

            return $response
                ->withJson($cat)
                ->withStatus($cat['statusCode']);
        }

    });

    $group->put('/add/subcategory/lower/{parent}', function ($request, $response, array $args)use($admin){
        $admin->setId($request->getAttribute('parent'));
        $params = $request->getParsedBody();
        //if its not jobs no add
        if ($request->getAttribute('parent') == 1 || $request->getAttribute('parent') ==  2 || $request->getAttribute('parent') ==  3 ){
            return $response
                ->withJson(array(
                    'success' => false,
                    'statusCode' => FORBIDEN,
                    'error' => array('type' => 'INTERNAL_SERVER_ERROR', 'message' => 'Not Applicable for Accommodation, Services and Jobs' )
                ))
                ->withStatus(FORBIDEN);
        }else{
            $name = $admin->validateParameter("Name", $params['name'], STRING);
            $desc = $admin->validateParameter("Details", $params['description'], STRING);

            if ($name['success']){
                $admin->setName($name['data']);
            }else{
                return $response
                    ->withJson($name)
                    ->withStatus($name['statusCode']);
            }

            if ($desc['success']){
                $admin->setDesc($desc['data']);
            }else{
                return $response
                    ->withJson($desc)
                    ->withStatus($desc['statusCode']);
            }

            $cat = $admin->addSubCat3();

            return $response
                ->withJson($cat)
                ->withStatus($cat['statusCode']);
        }

    });

});

// api authentication get token login
$app->post('/auth/login', function ($request, $response, array $args)use($admin) {

    $params = $request->getParsedBody();
    $settings = $this->get('settings');

    $admin->setEmail($params['email']);
    $admin->setPassword($params['password']);
    $admin->setSecret($settings['jwt']['secret']);

    $auth = $admin->apiLogin();
    return $response->withJson($auth)
        ->withStatus($auth['statusCode']);

});

//add api user account
$app->post('/auth/user/create', function ($request, $response, array $args)use($admin) {

    $params = $request->getParsedBody();
    $admin->setEmail($params['email']);
    $admin->setName($params['name']);
    $admin->setLastName($params['surname']);
    if ($params['password'] !== $params['confirmPassword']){
        return $response->withJson(array('statusCode' => FORBIDEN, 'error' => array('type' => 'PARAMETER_ERROR', 'message' => 'Passwords do not match')))
            ->withStatus(FORBIDEN);
    }
    $admin->setPassword($params['password']);
    $reg = $admin->apiUserCreate();
    return $response->withJson($reg)
        ->withStatus($reg['statusCode']);

});

//create random string key
$app->get('/api/random/string/{len}', function($request, $response, array $args)use($sms, $match){
    return $response->withJson(array('match data' => $match->allListing()));
});

$app->get('/api/random/string/2/{len}', function($request, $response, array $args)use($sms, $match, $user){

    return $response->withJson(array('requests' => $match->myRequests(19)));
});
function moveUploadedFile($directory,  $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8));
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}