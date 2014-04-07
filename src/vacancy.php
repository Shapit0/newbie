<?

$app->get('/vacancy/{vid}', function($vid) use ($app) {
	
	// Redirect to main
	
	if ($vid == 0) {
		return $app->redirect($app['url_generator']->generate('main'));
	}
	
	// Get vacancy & company & metro information
	
	$vacancy = $app['db'] -> fetchAssoc ("SELECT v.title, v.salary, v.mentor_photo, v.mentor_name, v.mentor_comment, v.description, v.pay, v.remote, v.fulltext, v.start, v.end, v.quest_link, v.work_requirements, v.work_charges, v.work_conditions, v.employment, c.title AS comptitle, c.logo AS complogo, c.url AS compurl,
										s.title AS spectitle, s.type AS type, s.id AS specid
										FROM `vacancies` v
										LEFT JOIN `companies` c
										ON v.company = c.id
										LEFT JOIN `vn_db_metro` m 
										ON m.metro_id = c.metro_id
										LEFT JOIN `specialization` s
										ON v.specialization_id = s.id
										WHERE v.id = '" . $vid . "'");
	
	foreach ($vacancy as $key => $v) {
		$vacancy[$key] = iconv("Windows-1251", "UTF-8", $v);		
	}
	
	$vacancy["start"] = date("d-m",strtotime($vacancy["start"]));
	$requirements = json_decode($vacancy["work_requirements"]);
	$charges = json_decode($vacancy["work_charges"]);
	$conditions = json_decode($vacancy["work_conditions"]);		
		
	return $app['twig']->render('vacancy.twig', array(
													'v' => $vacancy,
													'requirements' => $requirements,
													'charges' => $charges,
													'conditions' => $conditions,
													)); 
   
})

    ->value('vid', '0')
    ->bind('vacancy');
