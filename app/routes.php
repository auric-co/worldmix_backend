<?php
//Hpme Route
$app->get('/', App\Action\HomeAction::class)
    ->setName('Velocity API');
//System
include_once dirname(__FILE__). '/../app/src/system/System.php';
include_once dirname(__FILE__). '/../app/src/system/Admin.php';
include_once dirname(__FILE__). '/../app/src/system/User.php';

$op = new System();
$admin = new Admin();
$user = new User();
$container['upload_directory'] = __DIR__ . '/../public/img/uploads';

//user routes
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

    $group->put('/register', function($request, $response)use($op, $user) {
        $params = $request->getParsedBody();
        $pwd = $user->validateParameter('Password',$params['password'], STRING);
        $name = $user->validateParameter('First Name',$params['name'],STRING);
        $lastName = $user->validateParameter('Last Name',$params['surname'],STRING);
        $msisdn = $user->validateParameter('Mobile Number', $params['msisdn'], 'msisdn');

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
        $user->setEmail($params['email']);

        $code = $user->getActivationCode();

        return $response
            ->withJson($code)
            ->withStatus($code['statusCode']);
    });

    $group->post('/activate/account', function ($request, $response)use($user){
        $params = $request->getParsedBody();

        $user->setCode($params['code']);
        $user->setEmail($params['email']);

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
                    $service = $params['service_id'];
                    $name = $params['service'];
                    $user->setCategory($type);
                    $user->setId($service);
                    $user->setName($name);
                    $user->setToken($token);
                    $subscribe = $user->saveService();
                return $response
                    ->withJson($subscribe)
                    ->withStatus($subscribe['statusCode']);
                break;
            case "2":
                //accommodation
                //`thumbnails`
                $params = $request->getParsedBody();
                $token = $params['token'];
                $name = $user->validateParameter("Name", $params['name'], STRING);
                $subcat = $user->validateParameter("Sub Category1", $params['subcategory1'], INTEGER);
                $bedrooms = $user->validateParameter("# of Bedrooms", $params['bedrooms'], INTEGER);
                $dateVacant = $user->validateParameter("Start Date",$params['startDate'], STRING);
                $price = $user->validateParameter("Rent Price", $params['price'], MYSQLI_TYPE_DECIMAL);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $country = $user->validateParameter("Country",$params['country'], STRING);
                $address = $user->validateParameter("Address",$params['address'], STRING);
                $location = $user->validateParameter("Location", $params['location'], STRING, false);

                $user->setCategory($type);
                $user->setToken($token);
                if ($name['success']){
                    $user->setName($name['data']);
                }else{
                    return $response
                        ->withJson($name)
                        ->withStatus($name['statusCode']);
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

                if ($address['success']){
                    $user->setAddress($address['data']);
                }else{
                    return $response
                        ->withJson($address)
                        ->withStatus($address['statusCode']);
                }

                if ($location['success']){
                    $user->setLocation($location['data']);
                }else{
                    return $response
                        ->withJson($location)
                        ->withStatus($location['statusCode']);
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
                $subcat2 = $user->validateParameter("Job Category", $params, INTEGER);
                $notes = $user->validateParameter("Notes", $params['notes'], STRING);
                $deadline = $user->validateParameter("Deadline", $params['deadline'], STRING, false);
                $city = $user->validateParameter('Town', $params['town'], STRING);
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
                //`thumbnail`
                $params = $request->getParsedBody();
                $name = $user->validateParameter("Name", $params['name'], STRING);
                $subcat = $user->validateParameter("Mode of transport medium", $params['mode'], INTEGER);
                $subcat2 = $user->validateParameter("Type of vehicle", $params['type'], INTEGER);
                $subcat3 = $user->validateParameter("Vehicle Model", $params['vehicle'], INTEGER);
                $fuel = $user->validateParameter("Vehicle fuel", $params['fuel'], STRING);
                $transmission = $user->validateParameter("Transmission", $params['transmission'], STRING, false);
                $description = $user->validateParameter("Notes", $params['notes'], STRING);
                $location = $user->validateParameter("Location", $params['location'], STRING, false);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $price = $user->validateParameter("Price", $params['price'], MYSQLI_TYPE_DECIMAL);
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

                if ($description['success']){
                    $user->setDesc($description['data']);
                }else{
                    return $response
                        ->withJson($description)
                        ->withStatus($description['statusCode']);
                }

                if ($location['success']){
                    $user->setLocation($location['data']);
                }else{
                    return $response
                        ->withJson($location)
                        ->withStatus($location['statusCode']);
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

    $group->put('/subscribe/requests/{category}', function ($request, $response)use($user){
        $type = $request->getAttribute('category');
        switch ($type){
            case "1":
                $params = $request->getParsedBody();
                $token = $params['token'];
                $service = $params['service_id'];
                $user->setCategory($type);
                $user->setId($service);
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
                $subcat2 = $user->validateParameter("Type of vehicle", $params['type'], INTEGER);
                $subcat3 = $user->validateParameter("Vehicle Model", $params['vehicle'], INTEGER);
                $fuel = $user->validateParameter("Vehicle fuel", $params['fuel'], STRING);
                $transmission = $user->validateParameter("Transmission", $params['transmission'], STRING, false);
                $town = $user->validateParameter("Town", $params['town'], STRING);
                $price = $user->validateParameter("Price", $params['price'], MYSQLI_TYPE_DECIMAL);
                $price2 = $user->validateParameter('Top Price',$params['priceRange'], MYSQLI_TYPE_DECIMAL);
                $country = $user->validateParameter("Country", $params['country'], STRING);
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

                $vehicle = $user->vehichleSubscribe();
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
            ->withJson($matches)
            ->withStatus($matches['statusCode']);
    });
});

//system routes
$app->group('/api/system', function ($group)use($op){

    $group->get('/categories',function($request, $response)use($op){
        $categories = $op->Categories();
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
        $admin->setPermission($permission['data']);
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

    $group->get('/members/all', function($request, $response)use($admin){

        $admins = $admin->membersAll();
        return $response
            ->withJson($admins)
            ->withStatus($admins['statusCode']);
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
$app->get('/api/random/string/{len}', function($request, $response)use($op){
    return $response->withJson(array('key' => $op->createString($request->getAttribute('len'))));
});