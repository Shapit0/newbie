<?

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\DBAL\DriverManager;



////////////////////////////////////////////////////////////////////////////////////// Login

$app->get('/login', function (request $request) use ($app) {
	
	if ($app['security']->isGranted('ROLE_ADMIN') || $app['security']->isGranted('ROLE_MODERATOR')) {
          echo "ТЫ АДМИН";
    }  
	    
	var_dump($app['security']->getToken());    

    return $app['twig']->render('login.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));

})
    ->bind('login');
    
////////////////////////////////////////////////////////////////////////////////////// LOGOUT

$app->get('/logout', function(Request $request) use ($app) {
   
   
        
     return $app->redirect($app['url_generator']->generate('main'));
})

 ->bind('logout');    

////////////////////////////////////////////////////////////////////////////////////// Login check

$app->POST('/admin/login_check', function(Request $request) use ($app) {
   
   return $app->redirect($app['url_generator']->generate('login'));
});    

////////////////////////////////////////////////////////////////////////////////////// Registration

$app->match('/registration', function(Request $request) use ($app) {
   
     $data=[];


    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('username')
        ->add('password', 'password') 
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
        $data['roles'] = 'ROLE_USER';

        if (strlen($data['password']) < 6) {
             $error .= "Слишком короткий пароль!";
        } else {
             $logins = $app['db'] -> fetchAll("SELECT login FROM users WHERE username ='" . $data['username'] . "'");
             if (!$logins) {
                  $error = "Success";   
                  $data['password']=$app['security.encoder.digest']->encodePassword($data['password'],'');
                  $app['db'] -> insert('users', $data);
             } else {
                  $error .= "Пользователь с таким именем уже существует!";
             }
        }
    }

    // display the form
    return $app['twig']->render('register.twig', array('form' => $form->createView(), 'error' => $error));

  

})

 ->bind('registration');


    
////////////////////////////////////////////////////////////////////////////////////// Admin Main
    

$app->get('/admin/', function() use ($app) {
	
	
	
    return $app['twig']->render('admin.twig');   
})

 ->bind('admin');


////////////////////////////////////////////////////////////////////////////////////// Admin Add company
    

$app->match('/admin/company/{id}', function(Request $request, $id) use ($app) {
	
	$kill = preg_match("/kill/",$id);

	if ($kill) {
		preg_match("/(.*)-/",$id, $res);
		$app['db'] -> delete('companies', array('id' => $res[1]));
		return $app->redirect($app['url_generator']->generate('admin_company'));		
	}
	
	if ($id != "") {
		$data = $app['db'] -> fetchAssoc ("SELECT * FROM companies WHERE id = '" . $id . "'");	
		$data["edit_num"] = $id;
		$data["logo_backup"] = $data["logo"];
		unset($data["logo"]);
		$required_att['required'] = false; 
	} else {
		$required_att['required'] = true;
	}
	$logo_required_text = ($required_att['required'])? "Загрузка логотипа обязательна" : "Загрузка логотипа не обязательна";
	
	$metro = $app['db'] -> fetchAll ("SELECT metro_id, title FROM vn_db_metro");
	
	foreach ($metro as $m) {
		$mtitle[$m["metro_id"]] = iconv("Windows-1251", "UTF-8", $m["title"]);				
	}	

	$maxid = $app['db'] -> fetchAssoc("SELECT max(id) FROM companies");
	$maxid++;
		
	$form = $app['form.factory']->createBuilder('form', $data)
            ->add('title')
            ->add('logo', 'file', $required_att)
            ->add('metro_id', 'choice', array(
                'choices' => $mtitle,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('url')    
            ->add('edit_num', 'hidden')
            ->add('logo_backup', 'hidden')            
            ->getForm();

     $form->handleRequest($request);
       
        if ($form->isValid()) {
            $data = $form->getData();
            
            // HTTP checker in URL
            
            $url = preg_match('/http/', $data["url"], $result);
            if (!$url) {
	            $data["url"] = "http://" . $data["url"];
            }
            
            // LOGO uploader
            if ($data["edit_num"] && !$data["logo"]) {
	            $data["logo"] = $data["logo_backup"];
            } else {            
	            $files = $request->files->get($form->getName());
	            $path = 'logos/';
	            $extension = $data['logo']->getClientOriginalName();
	            $extension = preg_match('/\.(.{3,4})$/', $extension, $result);
	            	if ($data["edit_num"]) {
			            $filename = $data["edit_num"] . "." . $result[1];	            
		            } else {
			            $filename = $maxid . "." . $result[1];
		            }
	            
	            $files['logo']->move($path,$filename);
	            $data['logo'] = $filename;
            }
            
            if ($data["edit_num"] > -1) {
	            $id = $data["edit_num"];
	            unset ($data["edit_num"]);
	            unset ($data["logo_backup"]);

	            $app['db'] -> update('companies', $data, array('id' => $id));
            } else {
            	unset ($data["edit_num"]);
	            unset ($data["logo_backup"]);
            	$app['db'] -> insert('companies', $data);	            
            }
            
            
             return $app->redirect($app['url_generator']->generate('admin_company'));
        }

	$companies = $app['db'] -> fetchAll ("SELECT * FROM companies");
	
    return $app['twig']->render('admin_company.twig', array('companies' => $companies, 'id' => $id, 'form' => $form->createView(), 'logo_required' => $logo_required_text));   
})

 ->value('id', '')
 ->bind('admin_company');