<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class TestController extends AbstractController
{
    /**
     * Test : index
     * @Route("/test", name="test_index")
     * @return
     */
    public function index()
    {
        return $this->render('test/index.html.twig');
    }

    /**
     * Query string
     * @Route("/test/aaa/{firstName}/{lastName}", name="test_aaa")
     * @param Request $request
     * @param         $firstName
     * @param         $lastName
     * @return
     */
    public function aaa($firstName, $lastName, Request $request)
    {
        $fn = $request->query->get('firstname', $firstName);
        $ln = $request->query->get('firstname', $lastName);
        return $this->render('test/aaa.html.twig', array(
            'firstname' => $fn,
            'lastname' => $ln,
        ));
    }

    /**
     * Session
     * @Route("/test/bbb", name="test_bbb")
     * @param SessionInterface $session
     */
    public function bbb(SessionInterface $session)
    {
        // store an attribute for reuse during a later user request
        //$session->set('foo', 'barrr');

        // get the attribute set by another controller in another request
        $foobar = $session->get('foo');

        // use a default value if the attribute doesn't exist
//        $filters = $session->get('filters', array());

        return $this->render('test/bbb.html.twig', array(
            'foobar' => $foobar
        ));
    }

    /**
     * Flash
     * @Route("/test/ccc", name="test_ccc")
     */
    public function ccc()
    {
        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );

        return $this->render('test/ccc.html.twig');
    }

    /**
     * Get datas (ajax, lang, get, post, server, file, cookie, header)
     * @Route("/test/ddd/{page}", name="test_ddd")
     */
    public function ddd(Request $request, $page)
    {
        $isAjax = $request->isXmlHttpRequest(); // is it an Ajax request?

        $getPrefLang = $request->getPreferredLanguage(array('en', 'fr'));

        // retrieve GET and POST variables respectively
        $get = $request->query->get('page', $page);
        $post = $request->request->get('page');

        // retrieve SERVER variables
        $server = $request->server->get('HTTP_HOST');

        // retrieves an instance of UploadedFile identified by foo
        $file = $request->files->get('foo');

        // retrieve a COOKIE value
        $cookie = $request->cookies->get('PHPSESSID');

        // retrieve an HTTP request header, with normalized, lowercase keys
        $header01 = $request->headers->get('host');
        $header02 = $request->headers->get('content_type');

        return $this->render('testŒ/ddd.html.twig', array(
            //'isAjax' => $isAjax,
            'getPrefLang' => $getPrefLang,
            'get' => $get,
            'post' => $post,
            'server' => $server,
            //'file' => $file,
            //'cookie' => $cookie,
            //'header01' => $header01,
            //'header02' => $header02,
        ));
    }

    /**
     * Json sample
     * @Route("/test/eee")
     */
    public function eee()
    {
        // returns '{"username":"jane.doe"}' and sets the proper Content-Type header
        return $this->json(array('username' => 'jane.doe'));

        // the shortcut defines three optional arguments
        // return $this->json($data, $status = 200, $headers = array(), $context = array());
    }

    /**
     * Download File (ex : pdf)
     * @Route("/test/fff")
     */
    public function fff(Request $request)
    {
        // send the file contents and force the browser to download it
        return $this->file('doc/aaa.pdf');

        //return $this->file('http://localhost:8001/doc/aaa.pdf');
    }

    /**
     * Display PDF
     * @Route("/test/ggg")
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function ggg()
    {
        // load the file from the filesystem
        $file = new File('doc/aaa.pdf');

        //return $this->file($file);

        // rename the downloaded file
        //return $this->file($file, 'custom_name.pdf');

        // display the file contents in the browser instead of downloading it
        return $this->file('doc/aaa.pdf', 'my_invoice.pdf', ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * Log
     * Source : https://symfony.com/doc/current/logging.html
     * @Route("/test/hhh")
     */
    public function hhh(LoggerInterface $logger)
    {
        $logger->info('xxxxx Test info xxxxx');
        $logger->error('xxxxx Test error xxxxx');
        $logger->critical('xxxxx Test critical xxxxx', array(
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ));
        return $this->render('test/hhh.html.twig');
    }

    /**
     * Encode CSV
     * Source : https://api.symfony.com/4.1/Symfony/Component/Serializer/Encoder/CsvEncoder.html#method_encode
     * @Route("/test/encoderCSV", name="test_encodercsv"))
     */
    public function encoderCSV()
    {
        $aaa = array(
            array(
                'aaa01' => 'bbb01',
                'aaa02' => 'bbb02',
                'aaa03' => 'bbb03',
            ),
            array(
                'aaa01' => 'ccc01',
                'aaa02' => 'ccc02',
                'aaa03' => 'ccc03',
            ),
            array(
                'aaa01' => 'ddd01',
                'aaa02' => 'ddd02',
                'aaa03' => 'ddd03',
            ),
        );

        $csv = new CsvEncoder();
        $res = $csv->encode($aaa, 'csv', array(';', '"', '\\', '.'));
        return $this->render('test/encodeCSV.html.twig', ['res' => $res]);
    }

    /**
     * Encode XML
     * Source :
     * - https://api.symfony.com/4.1/Symfony/Component/Serializer/Encoder/XmlEncoder.html#method_encode
     * - https://symfony.com/doc/current/components/serializer.html#id1
     * @Route("/test/encoderXML", name="test_encoderxml")
     */
    public function encoderXML()
    {
        $aaa = array(
            array(
                'aaa01' => 'bbb01',
                'aaa02' => 'bbb02',
                'aaa03' => 'bbb03',
            ),
            array(
                'aaa01' => 'ccc01',
                'aaa02' => 'ccc02',
                'aaa03' => 'ccc03',
            ),
            array(
                'aaa01' => 'ddd01',
                'aaa02' => 'ddd02',
                'aaa03' => 'ddd03',
            ),
        );

        $xml = new XmlEncoder();
        $res = $xml->encode($aaa, 'xml', array('xml_format_output' => true, 'xml_encoding' => 'utf-8'));
        return $this->render('test/encodeXML.html.twig', ['res' => $res]);
    }

    /**
     * .env : config for use MailTrap.io (test)
     * Source : https://symfony.com/doc/current/email.html
     * Account : mailtrap@eewee.fr
     *
     * @Route("/test/email")
     */
    public function email(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('xxx@eewee.fr')
            ->setTo('aaa@eewee.fr')
            ->setBody(
                $this->renderView(
                // /templates/emails/registration.html.twig
                    'emails/email01.html.twig',
                    array('name' => "Eric")
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/test.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $mailer->send($message);

        return $this->render('test/index.html.twig');
    }
}
