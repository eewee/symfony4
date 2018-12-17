<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlacklistCheckController extends AbstractController
{
    /**
     * Example with "id" required
     *
     * @Route("/blacklist/check", name="blacklist_check", requirements={"id"="\d+"})
     */
    public function index($id = 123)
    {
        return $this->render('blacklist_check/index.html.twig', [
            'controller_name' => 'BlacklistCheckController',
        ]);
    }

    /**
     * @Route("/blacklist/check/run/{ip}", name="blacklist_check_run")
     */
    public function run($ip, \Swift_Mailer $mailer, LoggerInterface $logger)
    {
        // IP ok or not ?
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $logger->info('BLCheck - IP not valid', array('IP'=>$ip));
            echo 'error ip not valid';
            return false;
        }

        // /blacklist/check/run/209.85.210.179
        $url = $this->generateUrl(
            'blacklist_check_run',
            array('ip' => $ip)
        );

        // EMAIL
        $emailTo = "blcheck@eewee.fr";
        $emailSubject = "[Blacklist] detection (".$ip.")";

        // LIST RBL (Realtime Blackhole List)
        $rbls = [
            'b.barracudacentral.org',
            'cbl.abuseat.org',
            'http.dnsbl.sorbs.net',
            'misc.dnsbl.sorbs.net',
            'socks.dnsbl.sorbs.net',
            'web.dnsbl.sorbs.net',
            'dnsbl-1.uceprotect.net',
            'dnsbl-3.uceprotect.net',
            'sbl.spamhaus.org',
            'zen.spamhaus.org',
            'psbl.surriel.com',
            'dnsbl.njabl.org',
            'rbl.spamlab.com',
            'noptr.spamrats.com',
            'cbl.anti-spam.org.cn',
            'dnsbl.inps.de',
            'httpbl.abuse.ch',
            'korea.services.net',
            'virus.rbl.jp',
            'wormrbl.imp.ch',
            'rbl.suresupport.com',
            'ips.backscatterer.org',
            'opm.tornevall.org',
            'multi.surbl.org',
            'tor.dan.me.uk',
            'relays.mail-abuse.org',
            'rbl-plus.mail-abuse.org',
            'access.redhawk.org',
            'rbl.interserver.net',
            'bogons.cymru.com',
            'bl.spamcop.net',
            'dnsbl.sorbs.net',
            'dul.dnsbl.sorbs.net',
            'smtp.dnsbl.sorbs.net',
            'spam.dnsbl.sorbs.net',
            'zombie.dnsbl.sorbs.net',
            'dnsbl-2.uceprotect.net',
            'pbl.spamhaus.org',
            'xbl.spamhaus.org',
            'bl.spamcannibal.org',
            'ubl.unsubscore.com',
            'combined.njabl.org',
            'dyna.spamrats.com',
            'spam.spamrats.com',
            'cdl.anti-spam.org.cn',
            'drone.abuse.ch',
            'dul.ru',
            'short.rbl.jp',
            'spamrbl.imp.ch',
            'virbl.bit.nl',
            'dsn.rfc-ignorant.org',
            'dsn.rfc-ignorant.org',
            'netblock.pedantic.org',
            'ix.dnsbl.manitu.net',
            'rbl.efnetrbl.org',
            'blackholes.mail-abuse.org',
            'dnsbl.dronebl.org',
            'db.wpbl.info',
            'query.senderbase.org',
            'bl.emailbasura.org',
            'combined.rbl.msrbl.net',
            'multi.uribl.com',
            'black.uribl.com',
            'cblless.anti-spam.org.cn',
            'cblplus.anti-spam.org.cn',
            'blackholes.five-ten-sg.com',
            'sorbs.dnsbl.net.au',
            'rmst.dnsbl.net.au',
            'dnsbl.kempt.net',
            'blacklist.woody.ch',
            'rot.blackhole.cantv.net',
            'virus.rbl.msrbl.net',
            'phishing.rbl.msrbl.net',
            'images.rbl.msrbl.net',
            'spam.rbl.msrbl.net',
            'spamlist.or.kr',
            'dnsbl.abuse.ch',
            'bl.deadbeef.com',
            'ricn.dnsbl.net.au',
            'forbidden.icm.edu.pl',
            'probes.dnsbl.net.au',
            'ubl.lashback.com',
            'ksi.dnsbl.net.au',
            'uribl.swinog.ch',
            'bsb.spamlookup.net',
            'dob.sibl.support-intelligence.net',
            'url.rbl.jp',
            'dyndns.rbl.jp',
            'omrs.dnsbl.net.au',
            'osrs.dnsbl.net.au',
            'orvedb.aupads.org',
            'relays.nether.net',
            'relays.bl.gweep.ca',
            'relays.bl.kundenserver.de',
            'dialups.mail-abuse.org',
            'rdts.dnsbl.net.au',
            'duinv.aupads.org',
            'dynablock.sorbs.net',
            'residential.block.transip.nl',
            'dynip.rothen.com',
            'dul.blackhole.cantv.net',
            'mail.people.it',
            'blacklist.sci.kun.nl',
            'all.spamblock.unit.liu.se',
            'spamguard.leadmon.net',
            'csi.cloudmark.com',
        ];

        $rev         = join('.', array_reverse(explode('.', trim($ip))));
        $i           = 1;
//        $rbl_count   = count($rbls);
        $listed_rbls = [];
        foreach ($rbls as $rbl)
        {
//            printf('Checking %s, %d of %d... ', $rbl, $i, $rbl_count);
            $lookup = sprintf('%s.%s', $rev, $rbl);
            $listed = gethostbyname($lookup) !== $lookup;
//            printf('[%s]%s', $listed ? 'LISTED' : 'OK', PHP_EOL);
//            echo "<br>";
            if ( $listed ) {
                $listed_rbls[] = $rbl;
            }
            $i++;
        }
//        echo "<hr>";
//        printf('%s listed on %d of %d known blacklists%s', $ip, count($listed_rbls), $rbl_count, PHP_EOL);
//        echo "<hr>";
        if ( !empty($listed_rbls) )
        {
//            printf('%s <b>listed on %s%s</b>', $ip, join(', ', $listed_rbls), PHP_EOL);
//            echo "<hr>";

            $emailContent = "ip : ".$ip."
";
            $emailContent .= "liste sur : ".join(', ', $listed_rbls);
        }




        // Send email
        $message = (new \Swift_Message('BL Check'))
            ->setFrom('sf4@eewee.fr')
            ->setTo($emailTo)
            ->setSubject($emailSubject)
            ->setBody(
                $this->renderView(
                // /templates/emails/registration.html.twig
                    'emails/blcheck01.html.twig',
                    array(
                        'content' => $emailContent
                    )
                ),
                'text/html'
            )
            /*
            // Text
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

        return $this->render('blacklist_check/run.html.twig', [
            'url' => $url
        ]);
    }

    /**
     * Error 404
     *
     * @Route("/blacklist/check/error404", name="blacklist_check_error404")
     */
    public function err404()
    {
        $product = false;
        if (!$product)
        {
            throw $this->createNotFoundException('The product does not exist');

            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
        }

        return $this->render('blacklist_check/error404.html.twig');
    }

    /**
     * Error 500
     *
     * @Route("/blacklist/check/error500", name="blacklist_check_error500")
     */
    public function err500()
    {
        // this exception ultimately generates a 500 status error
        throw new \Exception('Something went wrong!');

        return $this->render('blacklist_check/error500.html.twig');
    }

}
