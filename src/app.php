<? 

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/UserProvider.php';

use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Application\UrlGeneratorTrait;
use Silex\Application\SecurityTrait;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../web/views',
));



$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'alcospb_new',
            'user' => '045926221_new',
            'password' => 'OFTcImTsgnUB',
            'charset' => 'utf8',
        )
));

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
    	'index' => array(
                'pattern' => '^$',
                'anonymous' => true,
        ),     
    	'vacancy' => array(
                'pattern' => '^vacancy/.*$',
                'anonymous' => true,
	    ),     
     	'login' => array(
                'pattern' => '^/login.*$',
                'anonymous' => true,
	    ),         
        'secured' => array(
            'pattern' => '^/admin1/.*$',
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'logout' => array('logout_path' => '/logout'),
            'users' => $app->share(function () use ($app) {
                      return new UserProvider($app['db']);
                 }),
         ),    
    ),
));


$app['security.access_rules'] = array(
    array('^/admin1/.*$', 'ROLE_ADMIN'),
);



return $app;