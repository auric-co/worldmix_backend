<?php
//Hpme Route
$app->get('/', App\Action\HomeAction::class)
    ->setName('Velocity API');
//System
include_once dirname(__FILE__). '/../app/src/system/System.php';
include_once dirname(__FILE__). '/../app/src/system/Admin.php';
include_once dirname(__FILE__). '/../app/src/system/MembershipNumber.php';
include_once dirname(__FILE__). '/../app/src/system/Blog.php';

$op = new System();
$admin = new Admin();
$memb = new MembershipNumber();
$blog = new Blog();
$clients = new Clients();
$container['upload_directory'] = __DIR__ . '/../public/img/uploads';

//API  Routes
$app->group('/api/subscriptions', function ($group) use ($op, $app) {
    $group->get('/packages', function($request,  $response)use($op) {
        $packages = $op->packages();
        return $response->withJson($packages)->withStatus($packages['statusCode']);
    });

});
//clients routes

$app->group('/api/client', function ($group)use($clients){

    $group->post('/login', function ($request, $response)use($clients){
        $params = $request->getParsedBody();
    });

    $group->put('/register', function ($request, $response)use($clients){
        $params = $request->getParsedBody();
    });

    $group->get('/wellness/classes', function ($request, $response)use($clients){

    });


});
//member routes
$app->group('/api/member', function ($group)use($op, $memb){

    $group->post('/login', function ($request, $response)use($op){
        $params = $request->getParsedBody();
        $op->setMsisdn($op->escape_data($params['msisdn']));
        $op->setPin($op->escape_data($params['pin']));
        $login = $op->login();
        return $response
            ->withJson($login)
            ->withStatus($login['statusCode']);
    });

    $group->put('/register', function($request, $response)use($op, $memb) {
        $params = $request->getParsedBody();
        $pwd = $op->validateParameter('Password',$params['password'], STRING);
        $name = $op->validateParameter('First Name',$params['name'],STRING);
        $lastName = $op->validateParameter('Last Name',$params['surname'],STRING);

        if($name['success'] == true){
            $op->setName($name['data']);
        }else{
            return $response
                ->withJson($name)
                ->withStatus($name['statusCode']);
        }

        if ($lastName['success'] == true){
            $op->setLastName($lastName['data']);
        }else{
            return $response
                ->withJson($lastName)
                ->withStatus($lastName['statusCode']);
        }

        if ($pwd['success']){
            if ($pwd['data'] == $params['confirmPassword'])
                $op->setPassword($pwd['data']);
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

        $register = $op->Register();

        return $response
            ->withJson($register)
            ->withStatus($register['statusCode']);
    });

    $group->post('/details', function ($request, $response)use($op){
        $params = $request->getParsedBody('token');
        $op->setTkn($params['token']);
        $details = $op->getDetails();

        return $response
            ->withJson($details)
            ->withStatus($details['statusCode']);
    });

    //change pin
    $group->patch('/settings/password/change', function($request, $response)use($op){
        $params = $request->getParsedBody();

        $op->setTkn($params['token']);
        $op->setPassword($params['password']);
        $op->setNewPassword($params['newPassword']);

        $data = $op->changePassword();
        return $response
            ->withJson($data)
            ->withStatus($data['statusCode']);
    });

});

//admin routes
$app->group('/api/admin', function ($group)use($admin, $blog){

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

    $group->post('/profile/upload/image/{id}', function ($request, $response)use($blog){

        $id = $request->getAttribute('id');
        $directory = $this->get('upload_directory');
        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['file'];
        $path = $directory."/blog/thumbnails/";
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

            $filename = moveUploadedFile($path, $uploadedFile);
            $blog->saveImage($id, "thumbnail", $filename);
            //save the  name in the appropriate field
            return $response->withStatus(SUCCESS_RESPONSE)->withHeader("Location", "http://blog/upload.php?success=true&item=".$id);

        }else{

            return $response->withStatus(INTERNAL_SERVER_ERROR)->withHeader("Location", "http://localhost:8080/blog/upload.php?success=false&item=".$id);

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
$app->get('/api/random/string/{len}', function($request, $response)use($op){
    return $response->withJson(array('key' => $op->createString($request->getAttribute('len'))));
});