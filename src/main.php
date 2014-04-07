<?

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\DBAL\DriverManager;



$app->get('/', function() use ($app) {
    
echo "<a href='/vacancy/1'>Вакансия 1</a>";
    
  	return $app['twig']->render('main.twig');

})
    ->bind('main');



$app->match('/ajax', function (request $request) use ($app) {

	$income = $request->getContent();
	$res = json_decode($income);
	
	$type = $res -> type;
	$remote = $res -> remote;
	$pay = $res -> pay;
	$specialization = $res -> specialization;
	$employment = $res -> employment;
	
	$vacancies = $app['db'] -> fetchAll ("SELECT v.title, v.description, v.start, v.end, v.salary, v.employment AS employment, v.pay AS pay, v.remote AS remote,
										c.logo, 
										m.title AS metrotitle,
										s.title AS spectitle, s.type AS type, s.id AS specid
										FROM `vacancies` v
										LEFT JOIN `companies` c
										ON v.company = c.id
										LEFT JOIN `vn_db_metro` m
										ON c.metro_id = m.metro_id
										LEFT JOIN `specialization` s
										ON v.specialization_id = s.id
										WHERE v.start > '" . $data . "';
			 							");
 							
 	foreach ($vacancies as $key => $v) {
 		$vacancies[$key]["title"] = iconv("Windows-1251", "UTF-8", $v["title"]);
 		$vacancies[$key]["description"] = iconv("Windows-1251", "UTF-8", $v["description"]); 		
 		$vacancies[$key]["metrotitle"] = iconv("Windows-1251", "UTF-8", $v["metrotitle"]); 		 		
 		$vacancies[$key]["spectitle"] = iconv("Windows-1251", "UTF-8", $v["spectitle"]); 		 		 		
 		
	 	if (($type && $v["type"] != $type) || ($specialization && $v["specid"] != $specialization) || ($employment && $v["employment"] != $employment) || ($pay && $v["pay"] != $pay) || ($remote && $v["remote"] != $remote)) {
		 	unset($vacancies[$key]);
	 	}
	 }

	$vacancies = json_encode($vacancies); 							

    return new Response(
            json_encode($vacancies),
            200,
            ['Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*']
        );

    
});

////////////////////////////////////////////////////////////////////////////////////// test of ajax

$app->match('/ajaxtest', function (Request $request) use ($app) {

	return $app['twig']->render('ajax.twig');
	
});    

