<?php
/**
 * Project: MCSuite
 * File: VisitorHelper.php
 * User: Maxim
 * Date: 04/03/13
 * Time: 13:46
 */

namespace Maxim\CMSBundle\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class StatisticHelper extends controller{

    protected $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getVisitorsOnline()
    {
        $em             = $this->doctrine->getManager();

        /*if (!$cacheDriver->contains('_db_statistic_10minutes')) {*/
            $visitor        = $em->createQuery("SELECT COUNT(v.ip) as online FROM MaximCMSBundle:Visitor v WHERE v.time >= :time ORDER BY v.time DESC")->setParameter("time", (time() - 600));
            $visitor->useResultCache(true);
            $visitor->setResultCacheLifetime(true, 300, "_db_statistic_10minutes");
            $visitors = $visitor->getResult();
            $cacheDriver->save('_db_statistic_10minutes', $visitors);
       /* }
        else
        {
            $visitors = $cacheDriver->fetch('_db_statistic_10minutes');
        }*/

       /* if (!$cacheDriver->contains('_db_statistic_user')) {*/
            $visitor_online = $em->createQuery("SELECT COUNT(v.ip) as online FROM MaximCMSBundle:Visitor v WHERE v.time > :time AND NOT v.user IS NULL ORDER BY v.time DESC")->setParameter("time", (time() + 600));
            $visitor_online->useResultCache(true);
            $visitor_online->setResultCacheLifetime(true, 300, "_db_statistic_user");
            $visitors_online        = $visitor_online->getResult();
            $cacheDriver->save('_db_statistic_user', $visitors);
      /*  }
        else
        {
            $visitors_online = $cacheDriver->fetch('_db_statistic_user');
        }*/

       /* if (!$cacheDriver->contains('_db_statistic_monthly'))
        {*/
            $qV             = $em->createQuery("SELECT COUNT(v.ip) as online FROM MaximCMSBundle:Visitor v WHERE v.time >= :time ORDER BY v.time DESC")->setParameter("time", mktime(0,0,0,date('m'),1,date('Y')));
            $qV->useResultCache(true);
            $qV->setResultCacheLifetime(true, 300, "_db_statistic_monthly");
            $visitors_month_unique  = $qV->getResult();
            $cacheDriver->save('_db_statistic_monthly', $visitors);
        /*}
        else
        {
            $visitors_month_unique = $cacheDriver->fetch('_db_statistic_monthly');
        }*/

        $data['visitors_guests']       = $visitors[0]['online'];
        $data['visitors_users']        = $visitors_online[0]['online'];
        $data['visitors_month_total']  = $visitors_month_unique[0]['online'];

        return $data;
    }
}